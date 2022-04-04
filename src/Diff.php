<?php

namespace App\Differ;

use function Functional\sort;

function genDiff($file1, $file2): array
{
    return [
        'status' => 'nested',
        'value' => iter($file1, $file2),
    ];
}

function iter($array1, $array2): array
{
    $array1Keys = array_keys($array1);
    $array2Keys = array_keys($array2);
    $uniqueKeys = array_unique(array_merge($array1Keys, $array2Keys));
    ksort($uniqueKeys);
    $uniqueKeys2 = array_flip($uniqueKeys);
    $sortedKeys = sort($uniqueKeys2, fn ($left, $right) => strcmp($left, $right));

    $result = array_map(function ($key) use ($array1, $array2, $uniqueKeys) {
        $key = $uniqueKeys[$key];
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
