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
     * @param string $name
     *
     * @return Client
     */
    private static function elasticsearch(string $name = null) : Client
    {
        if ($return = DI::get(__METHOD__, $name)) {
            return $return;
        }

        $config = DI::config()->get('elasticsearch/' . ($name ?: 'default'));

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
            [],
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

        return DI::set(__METHOD__, $name, $client);
    }
}
