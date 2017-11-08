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
     * @return string
     */
    public function getIndex() : ?string
    {
        return $this->index;
    }

    /**
     * @param string $index
     *
     * @return $this
     */
    public function setIndex(string $index = null) : self
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function getType() : ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type = null) : self
    {
        $this->type = $type;

        return $this;
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
