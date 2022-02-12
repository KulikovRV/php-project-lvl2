<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;
use function App\Formater\Stylish\stylish;
use function App\Parser\preparationOfFiles;

class StylishTest2 extends TestCase
{
    public function testStylish(): void
    {
        $path1 = __DIR__ . '/fixtures/file-1-nested.json';
        $path2 = __DIR__ . '/fixtures/file-4-nested.yaml';
        $path3 = __DIR__ . '/fixtures/file1.json';
        $path4 = __DIR__ . '/fixtures/file2.json';
        $pathResult = __DIR__ . '/fixtures/resultStylish.txt';

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);
        $this->assertFileExists($path3);
        $this->assertFileExists($path4);
        $this->assertFileExists($pathResult);

        $firstFile = preparationOfFiles($path1);
        $secondFile =  preparationOfFiles($path2);
        $thirdFile = preparationOfFiles($path3);
        $fourthFile =  preparationOfFiles($path4);


        $diff = genDiff($firstFile, $secondFile);
        $flatDiff = genDiff($thirdFile, $fourthFile);
//        $result = stylish($diff);
        $flatResult = stylish($flatDiff);


        $flatArrayExpects = [
            "- follow:" => false,
            "host:" => "hexlet.io",
            "- proxy:" => "123.234.53.22",
            "- timeout:" => 50,
            "+ timeout:" => 20,
            "+ verbose:" => true
        ];

          $this->assertEquals($flatArrayExpects, $flatResult);
//        $expects = file_get_contents($pathResult);
//        $this->assertNotEmpty($expects);
//        $this->assertEquals($expects, $result);
    }
}