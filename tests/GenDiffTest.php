<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;
use function App\Formater\Stylish\stylish;
use function App\Parser\preparationOfFiles;

class GenDiffTest extends TestCase
{
    public function testGenDiff()
    {
        $path1 = __DIR__ . '/fixtures/file-1-nested.json';
        $path2 = __DIR__ . '/fixtures/file-4-nested.yaml';
        $pathResult = __DIR__ . '/fixtures/resultStylish.txt';

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);

        $firstFile = preparationOfFiles($path1);
        $secondFile =  preparationOfFiles($path2);

        $diff = genDiff($firstFile, $secondFile);
        $result = stylish($diff);

        $expects = file_get_contents($pathResult);
        $this->assertNotEmpty($expects);
        $this->assertEquals($expects, $result);
    }
}