<?php
namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;
use function App\Formater\Stylish\stylish;

// класс UtilsTest наследует класс TestCase
// имя класса совпадает с именем файла

class StylishTest extends TestCase
{
    // Метод, функция определенная внутри класса
    // Должна начинаться со слова test
    // public – чтобы PHPUnit мог вызвать этот тест снаружи
    public function testStylish(): void
    {
        $file1 = __DIR__ . "/fixtures/file1.json";
        $file2 = __DIR__ . "/fixtures/file2.json";
        $file3 = __DIR__ . "/fixtures/file3.json";
        $file4 = __DIR__ . "/fixtures/file4.json";
        $file5 =  __DIR__ . "/fixtures/file5.yml";
        $file6 =  __DIR__ . "/fixtures/file6.yml";
        $file7 =  __DIR__ . "/fixtures/file7.yaml";
        $emptyJson = __DIR__ . "/fixtures/empty.json";
        $nestedFile1 = __DIR__ . "/fixtures/file-1-nested.json";
        $nestedFile2 = __DIR__ . "/fixtures/file-2-nested.json";
        $nestedFile3 = __DIR__ . "/fixtures/file-3-nested.yml";
        $nestedFile4 = __DIR__ . "/fixtures/file-3-nested.yml";

        $result1 = [
            "- follow" => false,
            "  host" => "hexlet.io",
            "- proxy" => "123.234.53.22",
            "- timeout" => 50,
            "+ timeout" => 20,
            "+ verbose" => true
        ];
        $result2 = [
            "- follow" => false,
            "+ follow" => true,
            "- host" => "hexlet.io",
            "+ host" => "code-basics.com",
            "- proxy" => "123.234.53.22",
            "+ proxy" => "123.234.53.24",
            "  timeout" => 50,
            "+ state" => 777
        ];
        $result3 = [
            "- follow" => false,
            "- host" => "hexlet.io",
            "- proxy" => "123.234.53.22",
            "- timeout" => 50
        ];
        $result4 = [
        ];
        $result5 = [
            "  follow" => false,
            "  host" => "hexlet.io",
            "  proxy" => "123.234.53.22",
            "  timeout" => 50
        ];

        $result6 = [
            "common:" => [
                "+ follow:" => false,
                "setting1:" => "Value 1",
                "- setting2:" => 200,
                "- setting3:" => true,
                "+ setting3:" => null,
                "+ setting4:" => "blah blah",
                "+ setting5:" => [
                    "key5:" => "value5"
                ],
                "setting6:" => [
                    "doge:" => [
                        "- wow:" => "",
                        "+ wow:" => "so much"
                    ],
                    "key:" => "value",
                    "+ ops:" => "vops"
                ],
            ],
            "group1:" => [
                "- baz:" => "bas",
                "+ baz:" => "bars",
                "foo:" => "bar",
                "- nest:" => [
                    "key:" => "value"
                ],
                "+ nest:" => "str"
            ],
            "- group2:" => [
                "abc:" => 12345,
                "deep:" => [
                    "id:" => 45
                ],
            ],
            "+ group3:" => [
                "deep:" => [
                    "id:" => [
                        "number:" => 45
                    ],
                ],
                "fee:" => 100500
            ]
        ];

        //Сравниваем файлы с форматом json
        $this->assertEquals($result1, stylish(genDiff($file1, $file2)));
        $this->assertEquals($result2, stylish(genDiff($file3, $file4)));
        $this->assertEquals($result3, stylish(genDiff($file1, $emptyJson)));
        $this->assertEquals($result4, stylish(genDiff($emptyJson, $emptyJson)));
        $this->assertEquals($result5, stylish(genDiff($file1, $file1)));

        //Сравниваем файлы с форматом yml и yaml
        $this->assertEquals($result1, stylish(genDiff($file5, $file6)));
        $this->assertEquals($result5, stylish(genDiff($file5, $file5)));
        $this->assertEquals($result1, stylish(genDiff($file7, $file6)));

        // Сравниваем файлы с форматом json и yaml/yml
        $this->assertEquals($result5, stylish(genDiff($file1, $file5)));
        $this->assertEquals($result1, stylish(genDiff($file7, $file2)));

        // Сравниваем файлы с вложенные структуры json
        $this->assertEquals($result6, stylish(genDiff($nestedFile1, $nestedFile2)));
    }
}