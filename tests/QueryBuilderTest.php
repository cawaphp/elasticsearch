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

/**
 * Сáша frameworks tests
 *
 * @author tchiotludo <http://github.com/tchiotludo>
 */
namespace CawaTest\ElasticSearch;

use Cawa\ElasticSearch\QueryBuilder;
use PHPUnit_Framework_TestCase as TestCase;

class QueryBuilderTest extends TestCase
{
    /**
     * @param array $sets
     * @param string $getKey
     * @param mixed $getValue
     *
     * @dataProvider setProvider
     */
    public function testSet(array $sets, string $getKey, $getValue)
    {
        $query = new QueryBuilder([]);
        foreach ($sets as $set) {
            $query->set($set[0], $set[1]);
        }

        $this->assertEquals($getValue, $query->get($getKey));
    }

    /**
     * @param array $sets
     * @param string $getKey
     * @param mixed $getValue
     *
     * @dataProvider setProvider
     */
    public function testAdd(array $sets, string $getKey, $getValue)
    {
        $query = new QueryBuilder([]);
        foreach ($sets as $set) {
            $query->set($set[0], $set[1]);
        }

        $this->assertEquals($getValue, $query->get($getKey));
    }

    /**
     * @param array $sets
     * @param string $getKey
     * @param mixed $getValue
     * @param string $json
     *
     * @dataProvider setProvider
     */
    public function testJson(array $sets, string $getKey, $getValue, string $json)
    {
        $query = new QueryBuilder([]);
        foreach ($sets as $set) {
            $query->set($set[0], $set[1]);
        }

        $this->assertEquals($json, json_encode($query));
    }

    /**
     * @param array $sets
     *
     * @dataProvider setProvider
     */
    public function testGetMustNotChangedArray(array $sets)
    {
        $query = new QueryBuilder([]);
        foreach ($sets as $set) {
            $query->get($set[0]);
        }

        $this->assertEquals(json_encode([]), json_encode($query));
    }

    /**
     * @param array $sets
     *
     * @dataProvider addExceptionProvider
     */
    public function testAddException(array $sets)
    {
        $query = new QueryBuilder([]);
        foreach ($sets as $i => $set) {
            if ($i > 0) {
                $this->expectException(\InvalidArgumentException::class);
            }

            $query->add($set[0], $set[1]);
        }
    }

    /**
     * @return array
     */
    public function addExceptionProvider()
    {
        return [
            [
                [
                    [
                        'aggregations/country/terms',
                        [
                            'field' => 'country',
                            'size' => 0,
                        ],
                    ],
                    [
                        'aggregations/country/terms',
                        [
                            'field' => 'country',
                            'size' => 0,
                        ],
                    ],
                ]
            ],
            [
                [
                    [
                        'query/bool/must[]',
                        [
                            'term' => [
                                'field' => 'country',
                                'size' => 0,
                            ],
                        ],
                    ],
                    [
                        'query/bool/must[0]',
                        [
                            'term' => [
                                'field' => 'country',
                                'size' => 0,
                            ],
                        ],
                    ],
                ]
            ],
            [
                [
                    [
                        'query/bool/must[1]',
                        [
                            'term' => [
                                'field' => 'country',
                                'size' => 0,
                            ],
                        ],
                    ],
                    [
                        'query/bool/must[]',
                        [
                            'term' => [
                                'field' => 'city',
                                'size' => 0,
                            ],
                        ],
                    ],
                    [
                        'query/bool/must[2]',
                        [
                            'term' => [
                                'field' => 'city',
                                'size' => 0,
                            ],
                        ],
                    ],

                ]
            ],
        ];
    }

    /**
     * @return array
     */
    public function setProvider()
    {
        return [
            [
                [
                    [
                        'aggregations/country/terms',
                        [
                            'field' => 'country',
                            'size' => 0,
                        ],
                    ],
                ],
                'aggregations/country/terms/field',
                'country',
                '{"aggregations":{"country":{"terms":{"field":"country","size":0}}}}',
            ],
            [
                [
                    [
                        'query/bool/must[]',
                        [
                            'term' => [
                                'field' => 'country',
                                'size' => 0,
                            ],
                        ],
                    ],
                ],
                'query/bool/must[0]/term/field',
                'country',
                '{"query":{"bool":{"must":[{"term":{"field":"country","size":0}}]}}}',
            ],
            [
                [
                    [
                        'query/bool/must[]',
                        [
                            'term' => [
                                'field' => 'country',
                                'size' => 0,
                            ],
                        ],
                    ],
                    [
                        'query/bool/must[]',
                        [
                            'term' => [
                                'field' => 'city',
                                'size' => 0,
                            ],
                        ],
                    ],
                ],
                'query/bool/must[1]/term/field',
                'city',
                '{"query":{"bool":{"must":[{"term":{"field":"country","size":0}},{"term":{"field":"city","size":0}}]}}}',
            ],
            [
                [
                    [
                        'query/bool/must[1]',
                        [
                            'term' => [
                                'field' => 'country',
                                'size' => 0,
                            ],
                        ],
                    ],
                    [
                        'query/bool/must[]',
                        [
                            'term' => [
                                'field' => 'city',
                                'size' => 0,
                            ],
                        ],
                    ],
                ],
                'query/bool/must[0]/term/field',
                null,
                '{"query":{"bool":{"must":[{"term":{"field":"city","size":0}},{"term":{"field":"country","size":0}}]}}}',
            ],
        ];
    }
}
