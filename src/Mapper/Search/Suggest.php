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
use Cawa\ElasticSearch\Mapper\Search\Suggest\Item;
use Cawa\Orm\Collection;

class Suggest extends AbstractMapper
{
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
     * @var Collection|Item[]
     */
    protected $suggests;

    /**
     * @return Item[]|Collection
     */
    public function getSuggests() : Collection
    {
        return $this->suggests;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $shards = $this->extract($result, '_shards');
        $this->shards = new Shards($shards);

        $this->suggests = new Collection();

        foreach (array_keys($result) as $name) {
            $this->suggests->add(new Item($name, $this->extract($result, $name)[0]));
        }
    }
}
