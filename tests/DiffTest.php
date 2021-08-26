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
        // Сначала идет ожидаемое значение (expected)
        // И только потом актуальное (actual)
        $this->assertEquals('', genDiff(''));
        $this->assertEquals('olleh', genDiff('hello'));
    }
}