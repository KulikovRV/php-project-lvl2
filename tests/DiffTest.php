<?php
namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;

// класс UtilsTest наследует класс TestCase
// имя класса совпадает с именем файла

class DiffTest extends TestCase
{
    // Метод, функция определенная внутри класса
    // Должна начинаться со слова test
    // public – чтобы PHPUnit мог вызвать этот тест снаружи
    public function testGenDiff(): void
    {
        $file1 = __DIR__ . "/fixtures/file1.json";
        $file2 = __DIR__ . "/fixtures/file2.json";
        $file3 = __DIR__ . "/fixtures/file3.json";
        $file4 = __DIR__ . "/fixtures/file4.json";
        $file5 =  __DIR__ . "/fixtures/file5.yml";
        $file6 =  __DIR__ . "/fixtures/file6.yml";
        $file7 =  __DIR__ . "/fixtures/file7.yaml";
        $emptyJson = __DIR__ . "/fixtures/empty.json";

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

        //Сравниваем файлы с форматом json
        $this->assertEquals($result1, genDiff($file1, $file2));
        $this->assertEquals($result2, genDiff($file3, $file4));
        $this->assertEquals($result3, genDiff($file1, $emptyJson));
        $this->assertEquals($result4, genDiff($emptyJson, $emptyJson));
        $this->assertEquals($result5, genDiff($file1, $file1));

        //Сравниваем файлы с форматом yml и yaml
        $this->assertEquals($result1, genDiff($file5, $file6));
        $this->assertEquals($result5, genDiff($file5, $file5));
        $this->assertEquals($result1, genDiff($file7, $file6));

        // Сравниваем файлы с форматом json и yaml/yml
        $this->assertEquals($result5, genDiff($file1, $file5));
        $this->assertEquals($result1, genDiff($file7, $file2));
    }
}