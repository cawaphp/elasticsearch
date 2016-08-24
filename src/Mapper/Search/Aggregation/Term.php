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

class Term extends AbstractAggregation
{
    use NameTrait;

    /**
     * @var int
     */
    protected $docCountErrorUpperBound;

    /**
     * @return int
     */
    public function getDocCountErrorUpperBound(): int
    {
        return $this->docCountErrorUpperBound;
    }

    /**
     * @var int
     */
    protected $sumOtherDocCount;

    /**
     * @return int
     */
    public function getSumOtherDocCount(): int
    {
        return $this->sumOtherDocCount;
    }

    /**
     * @var Collection|Bucket[]
     */
    protected $buckets;

    /**
     * @return Bucket[]|Collection
     */
    public function getBuckets()
    {
        return $this->buckets;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->docCountErrorUpperBound = $this->extract($result, 'doc_count_error_upper_bound');
        $this->sumOtherDocCount = $this->extract($result, 'sum_other_doc_count');

        $this->buckets = new Collection();
        $buckets = $this->extract($result, 'buckets');
        foreach ($buckets as $name => $bucket) {
            $this->buckets->add(new Bucket($bucket));
        }

        parent::map($result);
    }
}
