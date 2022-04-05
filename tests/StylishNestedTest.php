<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;

class StylishNestedTest extends TestCase
{
    public function testStylish(): void
    {
        $path1 = __DIR__ . '/fixtures/file-1-nested.json';
        $path2 = __DIR__ . '/fixtures/file-4-nested.yaml';

        $pathResult = __DIR__ . '/fixtures/resultStylish.txt';

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);

//        $firstFile = preparationOfFiles($path1);
//        $secondFile =  preparationOfFiles($path2);

//        $diff = genDiff($firstFile, $secondFile);
//        $result = stylish($diff);

        $expects1 = file_get_contents($pathResult);
        $result = genDiff($path1, $path2);

        $this->assertEquals($expects1, $result);
    }
}