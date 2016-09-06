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

namespace Cawa\ElasticSearch\Mapper\Search\Suggest;


use Cawa\ElasticSearch\Mapper\AbstractMapper;

class Option extends AbstractMapper
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
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
    protected $payload;

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->text = $this->extract($result, 'text');
        $this->score = $this->extract($result, 'score');
        $this->payload = $this->extract($result, 'payload');
    }
}
