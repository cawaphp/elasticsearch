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
use Cawa\ElasticSearch\Mapper\Search\Aggregation\AbstractAggregation;
use Cawa\ElasticSearch\Mapper\Search\Aggregation\AggregationTrait;
use Cawa\Orm\Collection;

class Search extends AbstractMapper
{
    use AggregationTrait;

    /**
     * @var int
     */
    protected $took;

    /**
     * @return int
     */
    public function getTook(): int
    {
        return $this->took;
    }

    /**
     * @var bool
     */
    protected $timeOut;

    /**
     * @return boolean
     */
    public function isTimeOut(): bool
    {
        return $this->timeOut;
    }

    /**
     * @var Hits
     */
    protected $hits;

    /**
     * @return Hits
     */
    public function getHits(): Hits
    {
        return $this->hits;
    }

    /**
     * @var Shards
     */
    protected $shards;

    /**
     * @return Shards
     */
    public function getShards(): Shards
    {
        return $this->shards;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->took = $this->extract($result, 'took');
        $this->timeOut = $this->extract($result, 'timed_out');

        $shards = $this->extract($result, '_shards');
        $this->shards = new Shards($shards);

        $hits = $this->extract($result, 'hits');
        $this->hits = new Hits($hits);

        $this->aggregations = new Collection();
        $aggregations = $this->extract($result, 'aggregations');
        if ($aggregations) {
            foreach ($aggregations as $name => $aggregation) {
                $this->aggregations->add(AbstractAggregation::create($aggregation, $name));
            }
        }
    }
}
