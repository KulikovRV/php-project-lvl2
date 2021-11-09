<?php

namespace App\Differ;

function genDiff($file1, $file2)
{

    return iter2($file1, $file2);
}

function iter1($array1, $array2, $diff = [])
{
    if (!is_array($array1)) {
        return $array1;
        /*return [
            'status' => '...',
            'value' => $array1
        ];*/
    }

    if (!is_array($array2)) {
        return $array2;
        /*return [
            'status' => '...',
            'value' => $array2
        ];*/
    }

    foreach ($array2 as $key2 => $value2) {
        if (array_key_exists($key2, $array1)) {
            $diff[$key2] = [
                'status' => 'saved',
                'value' => iter($array1[$key2], $value2, $diff)
            ];
        }

        if (!array_key_exists($key2, $array1)) {
            $diff[$key2] = [
                'status' => 'new',
                'value' => $value2
            ];
        }
    }

    foreach ($array1 as $key1 => $value1) {
        if (!array_key_exists($key1, $array2)) {
            $diff[$key1] = [
                'status' => 'deleted',
                'value' => $value1
            ];
        }
    }

    ksort($diff);
    //var_dump($diff);
    return $diff;
}

function iter2($array1, $array2)
{
    $array1Keys = array_keys($array1);
    $array2Keys = array_keys($array2);
    $uniqueKeys = array_unique(array_merge($array1Keys, $array2Keys));
    ksort($uniqueKeys);
    $uniqueKeys2 = array_flip($uniqueKeys);


    $result = array_map(function ($key) use ($array1, $array2, $uniqueKeys) {
        $key = $uniqueKeys[$key];
        $value1 = $array1[$key] ?? null;
        $value2 = $array2[$key] ?? null;

        if (!array_key_exists($key, $array2)) {
            return [
                'status' => 'deleted',
                'value' => $value1
            ];
        }

        if (!array_key_exists($key, $array1)) {
            return [
                'status' => 'new',
                'value' => $value2
            ];
        }

        if (is_array($value1) && is_array($value2)) {
            return [
                'status' => 'nested',
                'value' => iter2($value1, $value2)
            ];
        }

        if ($value1 !== $value2) {
            return [
                    'status' => 'modified',
                    'old value' => $value1,
                    'new value' => $value2
            ];
        }

        return [
            'status' => 'saved',
            'value' => $value1
        ];
    }, $uniqueKeys2);

//    var_dump($result);
    return $result;
}

