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

use Cawa\ElasticSearch\Mapper\AbstractMapper;
use Cawa\Orm\Collection;

class AbstractAggregation extends AbstractMapper
{
    use AggregationTrait;

    /**
     * @param array $result
     * @param string $name
     *
     * @return self
     */
    public static function create(array $result, string $name) : self
    {
        if (isset($result['doc_count'])) {
            $class = '\Cawa\ElasticSearch\Mapper\Search\Aggregation\Sub';
        } else if (isset($result['value'])) {
            $class = '\Cawa\ElasticSearch\Mapper\Search\Aggregation\Value';
        } else {
            $class = '\Cawa\ElasticSearch\Mapper\Search\Aggregation\Term';
        }

        $instance = new $class($result);
        $instance->name = $name;

        return $instance;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        if (sizeof($result) > 0) {
            $this->aggregations = new Collection();
            foreach ($result as $name => $aggregation) {
                $this->aggregations->add(AbstractAggregation::create($aggregation, $name));
                unset($result[$name]);
            }
        }
    }
}
