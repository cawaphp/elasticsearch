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

namespace Cawa\ElasticSearch\Mapper\Search;

use Cawa\ElasticSearch\Mapper\AbstractMapper;

class Hit extends AbstractMapper
{
    /**
     * @var string
     */
    protected $index;

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @var string
     */
    protected $type;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @var string
     */
    protected $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @var float
     */
    protected $score;

    /**
     * @return float
     */
    public function getScore(): float
    {
        return $this->score;
    }

    /**
     * @var array
     */
    protected $source;

    /**
     * @return array
     */
    public function getSource(): array
    {
        return $this->source;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->index = $this->extract($result, '_index');
        $this->type = $this->extract($result, '_type');
        $this->id = $this->extract($result, '_id');
        $this->score = $this->extract($result, '_score');
        $this->source = $this->extract($result, '_source');
    }
}
