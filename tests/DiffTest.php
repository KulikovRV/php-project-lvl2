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
        // Сначала идет ожидаемое значение (expected)
        // И только потом актуальное (actual)
        $this->assertEquals($result1, genDiff($file1, $file2));
    }
}