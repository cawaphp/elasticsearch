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

namespace Cawa\ElasticSearch;

class QueryBuilder  implements \Countable, \IteratorAggregate, \ArrayAccess, \JsonSerializable
{
    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * @param string $key
     *
     * @return array|mixed
     */
    private function &getReference(string $key)
    {
        $key = preg_replace('`\[([0-9]+)\]`', '/$1', $key);

        $keys = explode('/', $key);

        $ref = &$this->elements;
        $leave = false;

        while ($leave == false) {
            $key = array_shift($keys);

            $add = false;

            if (!is_null($key) && strpos($key, '[]') !== false) {
                $add = true;
                $key = substr($key, 0, -2);
            }

            if (!is_null($key) && isset($ref[$key]) && $ref[$key] instanceof \stdClass) {
                $ref[$key] = [];
            }

            if (is_null($key)) {
                $leave = true;
            } else if (isset($ref[$key]) && $ref[$key] instanceof QueryBuilder) {
                $ref = &$ref[$key]->elements;
            } else {
                $ref = &$ref[$key];
            }

            if ($add) {
                $ref = &$ref[];
            }
        }

        return $ref;
    }

    /**
     * @param string $key
     * @param $element
     *
     * @return $this
     */
    public function add(string $key, $element = null) : self
    {
        $ref = &$this->getReference($key);
        $ref = $element;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return array|mixed
     */
    public function get(string $key)
    {
        return $this->getReference($key);
    }

    /**
     * Required by interface IteratorAggregate.
     *
     * {@inheritdoc}
     */
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator($this->elements);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritdoc}
     */
    public function offsetExists($key)
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritdoc}
     */
    public function offsetGet($key)
    {
        return isset($this->elements[$key]) ? $this->elements[$key] : null;
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritdoc}
     */
    public function offsetSet($key, $value)
    {
        if (!isset($key)) {
            $this->elements[] = $value;
            return;
        }

        $this->elements[$key] = $value;
    }

    /**
     * Required by interface ArrayAccess.
     *
     * {@inheritdoc}
     */
    public function offsetUnset($key)
    {
        unset($this->elements[$key]);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->elements;
    }
}
