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

namespace Cawa\ElasticSearch\Mapper\Search\Aggregation;

use Cawa\Orm\Collection;

trait AggregationTrait
{
    /**
     * @var Collection|AbstractAggregation[]
     */
    protected $aggregations;

    /**
     * @return AbstractAggregation[]|Collection
     */
    public function getAggregations() : Collection
    {
        return $this->aggregations ?? new Collection();
    }

    /**
     * @param string $name
     *
     * @return AbstractAggregation|Bucket|Sub|Term|Value|null
     */
    public function getAggregation(string $name)
    {
        return $this->aggregations->findOne('getName', $name);
    }

}
