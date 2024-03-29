<?php

namespace Differ\Differ;

use function Functional\sort;
use function App\Parser\preparationOfFiles;
use function App\Formatters\getFormatter;

function genDiff(string $file1, string $file2, string $format = "stylish"): string
{
    $data1 = preparationOfFiles($file1);
    $data2 = preparationOfFiles($file2);
    $diff = buildTree($data1, $data2);
    return getFormatter($diff, $format);
}

function buildTree(array $data1, array $data2)
{
    return [
        'status' => 'root',
        'value' => iter($data1, $data2),
    ];
}

function iter(array $array1, array $array2): array
{
    $array1Keys = array_keys($array1);
    $array2Keys = array_keys($array2);
    $uniqueKeys = array_unique(array_merge($array1Keys, $array2Keys));
    $sortedKeys = sort($uniqueKeys, fn ($left, $right) => strcmp($left, $right));

    $result = array_map(function ($key) use ($array1, $array2) {
        $value1 = $array1[$key] ?? null;
        $value2 = $array2[$key] ?? null;

        if (!array_key_exists($key, $array2)) {
            return [
                'key' => $key,
                'status' => 'deleted',
                'value' => $value1
            ];
        }

        if (!array_key_exists($key, $array1)) {
            return [
                'key' => $key,
                'status' => 'new',
                'value' => $value2
            ];
        }

        if (is_array($value1) && is_array($value2)) {
            return [
                'key' => $key,
                'status' => 'nested',
                'value' => iter($value1, $value2)
            ];
        }

        if ($value1 !== $value2) {
            return [
                    'key' => $key,
                    'status' => 'modified',
                    'old value' => $value1,
                    'new value' => $value2
            ];
        }

        return [
            'key' => $key,
            'status' => 'saved',
            'value' => $value1
        ];
    }, $sortedKeys);

    return $result;
}
