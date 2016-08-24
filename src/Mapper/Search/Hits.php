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
use Cawa\Orm\Collection;

class Hits extends AbstractMapper
{
    /**
     * @var int
     */
    protected $total;

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @var float
     */
    protected $maxScore;

    /**
     * @return float
     */
    public function getMaxScore(): float
    {
        return $this->maxScore;
    }

    /**
     * @var Collection|Hit[]
     */
    protected $hits;

    /**
     * @return Hit[]|Collection
     */
    public function getHits() : Collection
    {
        return $this->hits;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->total = $this->extract($result, 'total');
        $this->maxScore = $this->extract($result, 'max_score');

        $this->hits = new Collection();
        $hits = $this->extract($result, 'hits');

        foreach ($hits as $hit) {
            $this->hits->add(new Hit($hit));
        }
    }
}
