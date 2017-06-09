<?php

/*
 * This file is part of the Сáша framework.
 *
 * (c) tchiotludo <http://github.com/tchiotludo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Cawa\ElasticSearch;

class Client extends \Elasticsearch\Client
{
    /**
     * @var string
     */
    private $index;

    /**
     * @param string $index
     */
    public function setIndex(string $index)
    {
        $this->index = $index;
    }

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function extractArgument(&$params, $arg)
    {
        if ($arg == 'index' && !isset($params[$arg]) && $this->index) {
            return $this->index;
        } elseif ($arg == 'type' && !isset($params[$arg]) && $this->type) {
            return $this->type;
        } else {
            return parent::extractArgument($params, $arg);
        }
    }
}
