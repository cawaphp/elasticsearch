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

class Sub extends AbstractAggregation
{
    use NameTrait;

    /**
     * @var int
     */
    protected $docCount;

    /**
     * @return int
     */
    public function getDocCount(): int
    {
        return $this->docCount;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->docCount = $this->extract($result, 'doc_count');

        parent::map($result);
    }
}
