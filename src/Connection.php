<?php

/*
 * This file is part of the Сáша framework.
 *
 * (c) tchiotludo <http://github.com/tchiotludo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types=1);

namespace Cawa\ElasticSearch;

use Cawa\Events\DispatcherFactory;
use Cawa\Events\ManualTimerEvent;
use Cawa\Events\TimerEvent;
use Elasticsearch\Transport;

class Connection extends \Elasticsearch\Connections\Connection
{
    use DispatcherFactory;

    /**
     * @var bool
     */
    private $connected = false;

    /**
     * {@inheritdoc}
     */
    public function performRequest(
        $method,
        $uri,
        $params = null,
        $body = null,
        $options = [],
        Transport $transport = null
    ) {
        $event = new TimerEvent('elasticsearch.query', [
            'method' => $method,
            'url' => null,
            'body' => $body,
            'total' => null,
        ]);

        try {
            $return = parent::performRequest(
                $method,
                $uri,
                $params,
                $body,
                $options,
                $transport
            );

            $result = (array) $return->wait();
            $this->emitEvents($event, $transport, $result);

            return $return;
        } catch (\Exception $exception) {
            $this->emitEvents($event, $transport);

            $class = get_class($exception);
            /** @var \Exception $throwException */
            $throwException = new $class(
                $exception->getMessage() . sprintf(' [Url: %s %s] [Query: %s] ', $method, $uri, json_encode($body)),
                $exception->getCode(),
                $exception
            );

            throw $throwException;
        }
    }

    /**
     * @param TimerEvent $event
     * @param Transport $transport
     * @param array $result
     */
    private function emitEvents(TimerEvent $event, Transport $transport, array $result = null)
    {
        $info = $transport->getLastConnection()->getLastRequestInfo();

        $data = [
            'url' => $info['response']['effective_url'],
        ];

        // connection duration
        if (!$this->connected) {
            $this->connected = true;

            $manualEvents = new ManualTimerEvent('elasticsearch.connect');
            $manualEvents->setStart($event->getStart());
            $manualEvents->setDuration(
                ($info['response']['transfer_stats']['namelookup_time'] +
                $info['response']['transfer_stats']['connect_time'])
            );

            $manualEvents->addData([
                'host' => $transport->getLastConnection()->getHost(),
                'dnsDuration' => $info['response']['transfer_stats']['namelookup_time'] * 1000,
                'connectDuration' => $info['response']['transfer_stats']['connect_time'] * 1000,
            ]);
            self::emit($manualEvents);
        }

        // total result size
        if (isset($result['hits']['total'])) {
            $data['total'] = $result['hits']['total'];
        }

        // server duration
        if (isset($result['took'])) {
            $manualEvents = new ManualTimerEvent('elasticsearch.serverQuery');
            $manualEvents->addData($data);
            $manualEvents->setStart(microtime(true) - ($result['took']/1000));
            $manualEvents->setDuration($result['took']/1000);
            self::emit($manualEvents);
        }

        $event->addData($data);
        self::emit($event);
    }
}
