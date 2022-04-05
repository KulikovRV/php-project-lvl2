<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;

class StylishFlatTest extends TestCase
{
    public function testStylish(): void
    {
        $path3 = __DIR__ . '/fixtures/file1.json';
        $path4 = __DIR__ . '/fixtures/file2.json';
        $pathResult = __DIR__ . '/fixtures/resultStylish3.txt';

        $this->assertFileExists($path3);
        $this->assertFileExists($path4);
        $this->assertFileExists($pathResult);

        $flatResult = genDiff($path3, $path4, 'stylish');
        $expects = file_get_contents($pathResult);
        $this->assertEquals($expects, $flatResult);
    }
}