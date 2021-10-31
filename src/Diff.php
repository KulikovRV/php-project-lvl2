<?php

namespace App\Differ;

function genDiff($file1, $file2)
{
    return iter($file1, $file2);
}

function iter($array1, $array2, $diff = [])
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

