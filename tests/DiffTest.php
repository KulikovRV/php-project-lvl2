<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;
use function App\Parser\preparationOfFiles;

class DiffTest extends TestCase
{
    public function testDiff(): void
    {
        $stylishFormat = 'stylish';
        $plainFormat = 'plain';
        $jsonFormat = 'json';

        $pathToNestedFile1 = __DIR__ . "/fixtures/file-1-nested.json";
        $pathToNestedFile2 = __DIR__ . "/fixtures/file-2-nested.json";
        $pathToNestedFile3 = __DIR__ . "/fixtures/file-3-nested.yml";
        $pathToNestedFile4 = __DIR__ . "/fixtures/file-4-nested.yaml";
        $nestedResult =  file_get_contents(__DIR__ . '/fixtures/resultStylish.txt');

        $this->assertEquals($nestedResult, genDiff($pathToNestedFile1, $pathToNestedFile4, $stylishFormat));
        $this->assertEquals($nestedResult, genDiff($pathToNestedFile1, $pathToNestedFile2, $stylishFormat));
        $this->assertEquals($nestedResult, genDiff($pathToNestedFile3, $pathToNestedFile4, $stylishFormat));

        $pathToFlatFile1 = __DIR__ . '/fixtures/file1.json';
        $pathToFlatFile2 = __DIR__ . '/fixtures/file2.json';
        $flatResult =  file_get_contents(__DIR__ . '/fixtures/resultStylish3.txt');
        $this->assertEquals($flatResult, genDiff($pathToFlatFile1, $pathToFlatFile2, $stylishFormat));

        $plainResult =  file_get_contents(__DIR__ . '/fixtures/resultPlain.txt');
        $this->assertEquals($plainResult, genDiff($pathToNestedFile1, $pathToNestedFile2, $plainFormat));

        $jsonResult = file_get_contents(__DIR__ . '/fixtures/resultJson.json');
        $this->assertEquals($jsonResult, genDiff($pathToNestedFile1, $pathToNestedFile2, $jsonFormat));

        $result1 = [
            'status'=> 'root',
            'value' => [
                0 => [
                    'key' => 'common',
                    'status' => 'nested',
                    'value' => [
                        0 => [
                            'key' => 'follow',
                            'status' => 'new',
                            'value' => false,
                        ],
                        1 => [
                            'key' => 'setting1',
                            'status' => 'saved',
                            'value' => 'Value 1'
                        ],
                        2 => [
                            'key' => 'setting2',
                            'status' => 'deleted',
                            'value' => 200
                        ],
                        3 => [
                            'key' => 'setting3',
                            'status' => 'modified',
                            'old value' => true,
                            'new value' => null
                        ],
                        4 => [
                            'key' => 'setting4',
                            'status' => 'new',
                            'value' => 'blah blah',
                        ],
                        5 => [
                            'key' => 'setting5',
                            'status' => 'new',
                            'value' => [
                                'key5' => 'value5'
                            ]
                        ],
                        6 => [
                            'key' => 'setting6',
                            'status' => 'nested',
                            'value' => [
                                'doge' => [
                                    'key' => 'doge',
                                    'status' => 'nested',
                                    'value' => [
                                        'wow' => [
                                            'key' => 'wow',
                                            'status' => 'modified',
                                            'old value' => '',
                                            'new value' => 'so much'
                                        ]
                                    ]
                                ],
                                'key' => [
                                    'key' => 'key',
                                    'status' => 'saved',
                                    'value' => 'value'
                                ],
                                'ops' => [
                                    'key' => 'ops',
                                    'status' => 'new',
                                    'value' => 'vops'
                                ]
                            ]
                        ]
                    ]
                ],
                2 => [
                    'key' => 'group1',
                    'status' => 'nested',
                    'value' => [
                        'baz' => [
                            'key' => 'baz',
                            'status' => 'modified',
                            'old value' => 'bas',
                            'new value' => 'bars'
                        ],
                        'foo' => [
                            'key' => 'foo',
                            'status' => 'saved',
                            'value' => 'bar'
                        ],
                        'nest' => [
                            'key' => 'nest',
                            'status' => 'modified',
                            'old value' => [
                                'key' => 'value'
                            ],
                            'new value' => 'str'
                        ]
                    ]
                ],
                3 => [
                    'key' => 'group2',
                    'status' => 'deleted',
                    'value' => [
                        'abc' => 12345,
                        'deep' => [
                            'id' => 45
                        ]
                    ]
                ],
                4 => [
                    'key' => 'group3',
                    'status' => 'new',
                    'value' => [
                        'deep' => [
                            'id' => [
                                'number' => 45
                            ]
                        ],
                        'fee' =>  100500
                    ]
                ]
            ]
        ];
    }
}
