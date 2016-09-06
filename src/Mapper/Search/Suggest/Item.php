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
use Cawa\Orm\Collection;

class Item extends AbstractMapper
{
    /**
     * @param string $name
     * @param array $result
     */
    public function __construct(string $name, array $result)
    {
        $this->name = $name;
        parent::__construct($result);
    }

    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

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
     * @var int
     */
    protected $offset;

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @var int
     */
    protected $length;

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @var Collection|Option[]
     */
    protected $options;

    /**
     * @return Option[]|Collection
     */
    public function getOptions() : Collection
    {
        return $this->options;
    }

    /**
     * @inheritdoc
     */
    protected function map(array &$result)
    {
        $this->text = $this->extract($result, 'text');
        $this->offset = $this->extract($result, 'offset');
        $this->length = $this->extract($result, 'length');

        $this->options = new Collection();
        $options = $this->extract($result, 'options');
        if ($options) {
            foreach ($options as $option) {
                $this->options->add(new Option($option));
            }
        }
    }
}
