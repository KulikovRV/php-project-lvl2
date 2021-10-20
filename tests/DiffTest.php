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
                // 'type' => 'node',
                'status' => 'saved',
                'value' => [
                    'follow' => [
                        // 'type' => 'children',
                        'status' => 'new',
                        'value' => false,
                    ],
                    'setting1' => [
                        // 'type' => 'children',
                        'status' => 'saved',
                        'value' => 'Value 1'
                    ],
                    'setting2' => [
                        // 'type' => 'children',
                        'status' => 'deleted',
                        'value' => 200
                    ],
                    'setting3' => [
                        //'type' => 'children',
                        'status' => 'modified',
                        'old value' => true,
                        'new value' => null
                    ],
                    'setting4' => [
                        //'type' => 'children',
                        'status' => 'new',
                        'value' => 'blah blah',
                    ],
                    'setting5' => [
                        //'type' => 'node',
                        'status' => 'new',
                        'value' => [
                            'key5' => [
                                //'type' => 'children',
                                'value' => 'value5',
                            ]
                        ]
                    ],
                    'setting6' => [
                        //'type' => 'node',
                        'status' => 'saved',
                        'value' => [
                            'doge' => [
                                //'type' => 'node',
                                'status' => 'saved',
                                'value' => [
                                    'wow' => [
                                        //'type' => 'children',
                                        'status' => 'modified',
                                        'old value' => '',
                                        'new value' => 'so much'
                                    ]
                                ]
                            ],
                            'key' => [
                                //'type' => 'children',
                                'status' => 'saved',
                                'value' => 'value'
                            ],
                            'ops' => [
                                //'type' => 'children',
                                'status' => 'new',
                                'value' => 'vops'
                            ]
                        ]
                    ]
                ]
            ],
            'group1' => [
                //'type' => 'node',
                'status' => 'saved',
                'value' => [
                    'baz' => [
                        //'type' => 'children',
                        'status' => 'modified',
                        'old value' => 'bas',
                        'new value' => 'bars'
                    ],
                    'foo' => [
                        //'type' => 'children',
                        'status' => 'saved',
                        'value' => 'bar'
                    ],
                    'nest' => [
                        //'type' => 'node',
                        'status' => 'modified',
                        'old value' => [
                            'key' => [
                                //'type' => 'children',
                                'value' => 'value'
                            ]
                        ],
                        'new value' => 'str'
                    ]
                ]
            ],
            'group2' => [
                //'type' => 'node',
                'status' => 'deleted',
                'value' => [
                    'abc' => [
                        //'type' => 'children',
                        'value' => 12345
                    ],
                    'deep' => [
                        //'type' => 'node',
                        'value' => [
                            'id' => [
                                //'type' => 'children',
                                'value' => 45
                            ]
                        ]
                    ]
                ]
            ],
            'group3' => [
                //'type' => 'node',
                'status' => 'new',
                'value' => [
                    'deep' => [
                        //'type' => 'node',
                        'value' => [
                            'id' => [
                                //'type' => 'node',
                                'value' => [
                                    'number' => [
                                        //'type' => 'children',
                                        'value' => 45
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'fee' => [
                        //'type' => 'children',
                        'value' => 100500
                    ]
                ]
            ]
        ];

        $this->assertEquals($result1, genDiff($nestedFile1, $nestedFile4));
    }
}
