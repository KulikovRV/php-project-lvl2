<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;
use function App\Differ\genDiff;

class JsonTest extends TestCase
{
    public function testPlain(): void
    {
        $path1 = __DIR__ . '/fixtures/file-1-nested.json';
        $path2 = __DIR__ . '/fixtures/file-4-nested.yaml';
        $pathResult = __DIR__ . '/fixtures/resultJson.json';

        $this->assertFileExists($path1);
        $this->assertFileExists($path2);

        $expects = file_get_contents($pathResult);
        $result = genDiff($path1, $path2, 'json');
        $this->assertEquals($expects, $result);
    }
}
