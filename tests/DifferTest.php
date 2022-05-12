<?php

namespace App\Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function gendiffProvider()
    {
        $expectedStylish =  file_get_contents(__DIR__ . '/fixtures/resultStylish.txt');
        $expectedPlain =  file_get_contents(__DIR__ . '/fixtures/resultPlain.txt');
        $expectedJson = file_get_contents(__DIR__ . '/fixtures/resultJson.json');

        $pathBeforeJson = __DIR__ . "/fixtures/before.json";
        $pathAfterJson = __DIR__ . "/fixtures/after.json";
        $pathBeforeYml = __DIR__ . "/fixtures/before.yml";
        $pathAfterYaml = __DIR__ . "/fixtures/after.yaml";

        return [
            'Json with Json stylish format' => [$expectedStylish, $pathBeforeJson, $pathAfterJson, 'stylish'],
            'Json with Yaml stylish format' => [$expectedStylish, $pathBeforeJson, $pathAfterYaml, 'stylish'],
            'Yml with Yaml stylish format' => [$expectedStylish, $pathBeforeYml, $pathAfterYaml, 'stylish'],
            'Json with Json plain format' => [$expectedPlain, $pathBeforeJson, $pathAfterJson, 'plain'],
            'Json with Yaml plain format' => [$expectedPlain, $pathBeforeJson, $pathAfterYaml, 'plain'],
            'Yml with Yaml plain format' => [$expectedPlain, $pathBeforeYml, $pathAfterYaml, 'plain'],
            'Json with Json json format' => [$expectedJson, $pathBeforeJson, $pathAfterJson, 'json'],
            'Json with Yaml json format' => [$expectedJson, $pathBeforeJson, $pathAfterYaml, 'json'],
            'Yml with Yaml json format' => [$expectedJson, $pathBeforeYml, $pathAfterYaml, 'json']
        ];
    }

    /**
     * @dataProvider gendiffProvider
     */
        public function testDiffer($expected, $path1, $path2, $format)
    {
        $diff = genDiff($path1, $path2, $format);
        $this->assertEquals($expected, $diff, $format);
    }
}
