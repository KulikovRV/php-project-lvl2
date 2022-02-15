<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;
use function App\Formater\Stylish\stylish;
use function App\Parser\preparationOfFiles;

class StylishTest extends TestCase
{
    public function testStylish(): void
    {
        $path1 = __DIR__ . '/fixtures/file-1-nested.json';
        $path2 = __DIR__ . '/fixtures/file-4-nested.yaml';
        $path3 = __DIR__ . '/fixtures/file-1-nestedReduced.json';
        $path4 = __DIR__ . '/fixtures/file-2-nestedReduced.json';

        $pathResult = __DIR__ . '/fixtures/resultStylish.txt';
        $pathResult2 = __DIR__ . '/fixtures/resultStylish2.txt';

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertFileExists($pathResult);

//        $firstFile = preparationOfFiles($path1);
//        $secondFile =  preparationOfFiles($path2);
        $firstFile = preparationOfFiles($path3);
        $secondFile =  preparationOfFiles($path4);

        $diff = genDiff($firstFile, $secondFile);
        $result = stylish($diff);

//        $expects = file_get_contents($pathResult);
        $expects = [
            'group1' => [
                '- baz:' => 'bas',
                '+ baz:' => 'bars',
                '  foo:' => 'bar'
            ]
        ];
        $expects2 = file_get_contents($pathResult2);
        $this->assertNotEmpty($expects);
//        $this->assertEquals($expects, $result);
        $this->assertEquals($expects, $result);
    }
}