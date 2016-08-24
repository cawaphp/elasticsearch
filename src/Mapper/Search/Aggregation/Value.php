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

class Value extends AbstractAggregation
{
    use NameTrait;

    /**
     * @var float
     */
    protected $value;

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->value = $this->extract($result, 'value');

        parent::map($result);
    }
}
