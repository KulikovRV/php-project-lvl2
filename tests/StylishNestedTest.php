<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;
use function App\Formater\Stylish\stylish;
use function App\Parser\preparationOfFiles;

class StylishNestedTest extends TestCase
{
    public function testStylish(): void
    {
        $path1 = __DIR__ . '/fixtures/file-1-nested.json';
        $path2 = __DIR__ . '/fixtures/file-4-nested.yaml';
        $path3 = __DIR__ . '/fixtures/file-1-nestedReduced.json';
        $path4 = __DIR__ . '/fixtures/file-2-nestedReduced.json';

        $pathResult1 = __DIR__ . '/fixtures/resultStylish.txt';
        $pathResult2 = __DIR__ . '/fixtures/resultStylish2.txt';

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertFileExists($pathResult1);
        $this->assertFileExists($pathResult2);


        $firstFile = preparationOfFiles($path1);
        $secondFile =  preparationOfFiles($path2);
        $thirdFile = preparationOfFiles($path3);
        $fourthFile =  preparationOfFiles($path4);

        $diff1 = genDiff($thirdFile, $fourthFile);
        $result1 = stylish($diff1);

        $diff2 = genDiff($firstFile, $secondFile);
        $result2 = stylish($diff2);


        $expects1 = file_get_contents($pathResult1);
        $expects2 = file_get_contents($pathResult2);
//        $expects2 = [
//            'group1' => [
//                '- baz' => 'bas',
//                '+ baz' => 'bars',
//                '  foo' => 'bar'
//            ]
//        ];
//        $expects3 = file_get_contents($pathResult2);

//        $this->assertNotEmpty($expects1);
//        $this->assertNotEmpty($expects2);

//        $this->assertEquals($expects, $result);

//        $this->assertEquals($expects2, $result1);
        $this->assertEquals($expects1, $result2);
    }
}