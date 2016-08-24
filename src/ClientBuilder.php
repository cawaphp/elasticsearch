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

use Elasticsearch\Transport;

class ClientBuilder extends \Elasticsearch\ClientBuilder
{
    /**
     * @inheritDoc
     */
    protected function instantiate(Transport $transport, callable $endpoint)
    {
        return new Client($transport, $endpoint);
    }
}
