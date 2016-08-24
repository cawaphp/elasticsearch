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

namespace Cawa\ElasticSearch\Mapper;

/**
 * @see https://github.com/dzlab/elastic-go/blob/355f7c99ab996028ce84aaed15ccae98423a4f16/response.go
 */
abstract class AbstractMapper
{
    /**
     * @param array $result
     *
     * @throws Exception
     */
    public function __construct(array &$result)
    {
        $this->map($result);

        if (sizeof($result) > 0) {
            throw new Exception('Non empty mapping result with data : ' . json_encode($result));
        }
    }

    /**
     * @param array $result
     * @param string $property
     * @param bool $mandatory
     *
     * @throws Exception
     *
     * @return mixed
     */
    protected function extract(array &$result, string $property, bool $mandatory = true)
    {
        $return = null;

        if (array_key_exists($property, $result)) {
            $return = $result[$property];
            unset($result[$property]);
        }

        if (is_null($result) && $mandatory) {
            throw new Exception(sprintf("Missing mandatory property '%s'", $property));
        }

        return $return;
    }

    /**
     * @param array $result
     */
    abstract protected function map(array &$result);
}
