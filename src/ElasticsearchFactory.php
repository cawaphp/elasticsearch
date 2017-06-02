<?php

/*
 * This file is part of the Сáша framework.
 *
 * (c) tchiotludo <http://github.com/tchiotludo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace Cawa\ElasticSearch;

use Cawa\Core\DI;
use Elasticsearch\Serializers\SmartSerializer;
use Psr\Log\NullLogger;

trait ElasticsearchFactory
{
    /**
     * @param string $name config key or class name
     *
     * @return Client
     */
    private static function elasticsearch(string $name = null) : Client
    {
        list($container, $config, $return) = DI::detect(__METHOD__, 'elasticsearch', $name);

        if ($return) {
            return $return;
        }

        $builder = new ClientBuilder();
        foreach ($config as $key => $value) {
            $method = "set$key";
            if (method_exists($builder, $method)) {
                $builder->$method($value);
                unset($config[$key]);
            }
        }

        $connectionFactory = new ConnectionFactory(
            ClientBuilder::defaultHandler(),
            [
                'client' => [
                    'headers' => [
                        'Content-type' => ['application/json'],
                        'Accept' => ['application/json']
                    ]
                ]
            ],
            new SmartSerializer(),
            new NullLogger(),
            new NullLogger()
        );

        $builder->setConnectionFactory($connectionFactory);

        /** @var Client $client */
        $client = $builder->build();

        if (isset($config['index'])) {
            $client->setIndex($config['index']);
        }

        if (isset($config['type'])) {
            $client->setType($config['type']);
        }

        return DI::set(__METHOD__, $container, $client);
    }
}
