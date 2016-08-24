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

class Shards extends AbstractMapper
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
     * @var int
     */
    protected $successful;

    /**
     * @return int
     */
    public function getSuccessful(): int
    {
        return $this->successful;
    }

    /**
     * @var int
     */
    protected $failed;

    /**
     * @return int
     */
    public function getFailed(): int
    {
        return $this->failed;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->total = $this->extract($result, 'total');
        $this->successful = $this->extract($result, 'successful');
        $this->failed = $this->extract($result, 'failed');
    }
}
