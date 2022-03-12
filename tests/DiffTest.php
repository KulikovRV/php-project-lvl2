<?php
namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;
use function App\Parser\preparationOfFiles;

class DiffTest extends TestCase
{
    public function testDiff(): void
    {
        $nestedFile1 = preparationOfFiles(__DIR__ . "/fixtures/file-1-nested.json");
        $nestedFile2 = preparationOfFiles(__DIR__ . "/fixtures/file-2-nested.json");
        $nestedFile3 = preparationOfFiles(__DIR__ . "/fixtures/file-3-nested.yml");
        $nestedFile4 = preparationOfFiles(__DIR__ . "/fixtures/file-4-nested.yaml");

        $result1 = [
            'common' => [
                'key' => 'common',
                'status' => 'nested',
                'value' => [
                    'follow' => [
                        'key' => 'follow',
                        'status' => 'new',
                        'value' => false,
                    ],
                    'setting1' => [
                        'key' => 'setting1',
                        'status' => 'saved',
                        'value' => 'Value 1'
                    ],
                    'setting2' => [
                        'key' => 'setting2',
                        'status' => 'deleted',
                        'value' => 200
                    ],
                    'setting3' => [
                        'key' => 'setting3',
                        'status' => 'modified',
                        'old value' => true,
                        'new value' => null
                    ],
                    'setting4' => [
                        'key' => 'setting4',
                        'status' => 'new',
                        'value' => 'blah blah',
                    ],
                    'setting5' => [
                        'key' => 'setting5',
                        'status' => 'new',
                        'value' => [
                            'key5' => 'value5'
                        ]
                    ],
                    'setting6' => [
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
            'group1' => [
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
            'group2' => [
                'key' => 'group2',
                'status' => 'deleted',
                'value' => [
                    'abc' => 12345,
                    'deep' => [
                        'id' => 45
                    ]
                ]
            ],
            'group3' => [
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
        ];

        $this->assertEquals($result1, genDiff($nestedFile1, $nestedFile4));
        $this->assertEquals($result1, genDiff($nestedFile1, $nestedFile2));
        $this->assertEquals($result1, genDiff($nestedFile3, $nestedFile4));
    }
}
