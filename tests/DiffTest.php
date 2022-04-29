<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DiffTest extends TestCase
{
    public function testDiff(): void
    {
        $stylishFormat = 'stylish';
        $plainFormat = 'plain';
        $jsonFormat = 'json';

        $pathToNestedFile1 = __DIR__ . "/fixtures/file-1-nested.json";
        $pathToNestedFile2 = __DIR__ . "/fixtures/file-2-nested.json";
        $pathToNestedFile3 = __DIR__ . "/fixtures/file-3-nested.yml";
        $pathToNestedFile4 = __DIR__ . "/fixtures/file-4-nested.yaml";
        $nestedResult =  file_get_contents(__DIR__ . '/fixtures/resultStylish.txt');

        $this->assertEquals($nestedResult, genDiff($pathToNestedFile1, $pathToNestedFile4, $stylishFormat));
        $this->assertEquals($nestedResult, genDiff($pathToNestedFile1, $pathToNestedFile2, $stylishFormat));
        $this->assertEquals($nestedResult, genDiff($pathToNestedFile3, $pathToNestedFile4, $stylishFormat));

        $expectedNested =  file_get_contents(__DIR__ . '/fixtures/resultStylish.txt');
        $this->assertEquals($expectedNested, genDiff($pathToNestedFile1, $pathToNestedFile2, $stylishFormat));

        $expectedPlain =  file_get_contents(__DIR__ . '/fixtures/resultPlain.txt');
        $this->assertEquals($expectedPlain, genDiff($pathToNestedFile1, $pathToNestedFile2, $plainFormat));

        $expectedJson = file_get_contents(__DIR__ . '/fixtures/resultJson.json');
        $this->assertEquals($expectedJson, genDiff($pathToNestedFile1, $pathToNestedFile2, $jsonFormat));
    }
}
