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

use Elasticsearch\Connections\ConnectionFactoryInterface;
use Elasticsearch\Connections\ConnectionInterface;
use Elasticsearch\Serializers\SerializerInterface;
use Psr\Log\LoggerInterface;

class ConnectionFactory implements ConnectionFactoryInterface
{
    /** @var  array */
    private $connectionParams;

    /** @var  LoggerInterface */
    private $logger;

    /** @var  LoggerInterface */
    private $tracer;

    /** @var callable */
    private $handler;

    /** @var SerializerInterface */
    private $serializer;
    /**
     * Constructor
     *
     * @param callable            $handler
     * @param array               $connectionParams
     * @param SerializerInterface $serializer
     * @param LoggerInterface     $logger
     * @param LoggerInterface     $tracer
     */
    public function __construct(callable $handler, array $connectionParams, SerializerInterface $serializer, LoggerInterface $logger, LoggerInterface $tracer)
    {
        $this->handler          = $handler;
        $this->connectionParams = $connectionParams;
        $this->logger           = $logger;
        $this->tracer           = $tracer;
        $this->serializer       = $serializer;
    }
    /**
     * @param $hostDetails
     *
     * @return ConnectionInterface
     */
    public function create($hostDetails)
    {
        return new Connection(
            $this->handler,
            $hostDetails,
            $this->connectionParams,
            $this->serializer,
            $this->logger,
            $this->tracer
        );
    }
}
