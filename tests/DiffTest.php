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
        $result1 = [
            "- follow:" => false,
            "  host:" => "hexlet.io",
            "- proxy:" => "123.234.53.22",
            "- timeout:" => 50,
            "+ timeout:" => 20,
            "+ verbose:" => true
        ];

        $file3 = __DIR__ . "/fixtures/file3.json";
        $file4 = __DIR__ . "/fixtures/file4.json";
        $result2 = [
            "- follow:" => false,
            "+ follow:" => true,
            "- host:" => "hexlet.io",
            "+ host:" => "code-basics.com",
            "- proxy:" => "123.234.53.22",
            "+ proxy:" => "123.234.53.24",
            "  timeout:" => 50,
            "+ state:" => 777
        ];

        $emptyJson = __DIR__ . "/fixtures/empty.json";
        $result3 = [
            "- follow:" => false,
            "- host:" => "hexlet.io",
            "- proxy:" => "123.234.53.22",
            "- timeout:" => 50
        ];
        $result4 = [
        ];
        $result5 = [
            "  follow:" => false,
            "  host:" => "hexlet.io",
            "  proxy:" => "123.234.53.22",
            "  timeout:" => 50
        ];

        // Сначала идет ожидаемое значение (expected)
        // И только потом актуальное (actual)
        $this->assertEquals($result1, genDiff($file1, $file2));
        $this->assertEquals($result2, genDiff($file3, $file4));
        $this->assertEquals($result3, genDiff($file1, $emptyJson));
        $this->assertEquals($result4, genDiff($emptyJson, $emptyJson));
        $this->assertEquals($result5, genDiff($file1, $file1));
    }
}