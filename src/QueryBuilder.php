<?php

/*
 * This file is part of the Сáша framework.
 *
 * (c) tchiotludo <http://github.com/tchiotludo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace Cawa\ElasticSearch;

class QueryBuilder implements \Countable, \IteratorAggregate, \ArrayAccess, \JsonSerializable
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
     * @param bool $create
     *
     * @return array|mixed
     */
    private function &getReference(string $key, bool $create = false)
    {
        $key = preg_replace('`\[([0-9]+)\]`', '/$1', $key);

        $keys = explode('/', $key);

        $ref = &$this->elements;
        $leave = false;

        while ($leave == false) {
            $currentKey = array_shift($keys);
            if (is_numeric($currentKey)) {
                $currentKey = (int) $currentKey;
            }

            $add = false;

            if (!is_null($currentKey) && strpos((string) $currentKey, '[]') !== false) {
                $add = true;
                $currentKey = substr($currentKey, 0, -2);
            }

            if (!is_null($currentKey) && !isset($ref[$currentKey]) && !$create) {
                $return = null;

                return $return;
            }

            if (!is_null($currentKey) && isset($ref[$currentKey]) && $ref[$currentKey] instanceof \stdClass && $create) {
                $ref[$currentKey] = [];
            }

            if (is_null($currentKey)) {
                $leave = true;
            } elseif (isset($ref[$currentKey]) && $ref[$currentKey] instanceof QueryBuilder) {
                $ref = &$ref[$currentKey]->elements;
            } else {
                $ref = &$ref[$currentKey];
            }

            if ($add && $create) {
                $ref = &$ref[];
            }
        }

        return $ref;
    }

    /**
     * @param string $key
     * @param array|self $element
     *
     * @return $this|self
     */
    public function set(string $key, $element = null) : self
    {
        $ref = &$this->getReference($key, true);
        $ref = is_object($element) ? clone $element : $element;

        return $this;
    }

    /**
     * @param string $key
     * @param array|self $element
     *
     * @return $this|self
     */
    public function add(string $key, $element = null) : self
    {
        $get = $this->getReference($key);

        if (!is_null($get) && strpos((string) $key, '[]') === false) {
            throw new \InvalidArgumentException(sprintf(
                "Already set path '%s' with value '%s'",
                $key,
                is_object($get) || is_array($get) ? json_encode($get) : $get
            ));
        }

        return $this->set($key, $element);
    }

    /**
     * @param string $key
     * @param array|self $element
     *
     * @return $this|self
     */
    public function addIfNotExist(string $key, $element = null) : self
    {
        $get = $this->getReference($key);

        if (is_null($get)) {
            $this->set($key, $element);
        }

        return $this;
    }

    /**
     * @param string $key
     *
     * @return array|self|mixed
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
     * Required by interface Countable
     *
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @param array $elements
     *
     * @return array
     */
    private function cleanUp(array $elements) : array
    {
        $isNumeric = false;
        $haveEmpty = false;
        foreach ($elements as $key => $value) {
            $isNumeric = is_numeric($key);
            if (is_null($value)) {
                $haveEmpty = true;
            } elseif (is_array($value)) {
                $elements[$key] = $this->cleanUp($value);
            } elseif ($elements instanceof QueryBuilder) {
                $elements[$key] = $this->cleanUp($value->elements);
            }
        }

        if ($isNumeric && ($haveEmpty || array_keys($elements) !== range(0, count($elements) - 1))) {
            $return = array_filter($elements);
            sort($return);

            return $return;
        }

        return $elements;
    }

    /**
     * Required by interface JsonSerializable
     *
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->cleanUp($this->elements);
    }
}
