<?php

namespace App\Differ;

use function App\Parser\preparationOfFiles;

// - есть в первом файле, в во втором нет,
// - одинаковые ключи, разные значения
// + появился во втором файле, уникальные данные
// + измененные данные
// отсортированы по алфавиту
// как реализовать плюс и минус ?
// на выходе json ?
// пока работаем с плоскими массивоми

// {
//  - follow: false
//    host: hexlet.io
//  - proxy: 123.234.53.22
//  - timeout: 50
//  + timeout: 20
//  + verbose: true
//}
// [proxy=> [value => 123...., staus => deleted]] ; вот такой пример если?)

function genDiff($pathToFile1, $pathToFile2): array
{
    $file1 = preparationOfFiles($pathToFile1);
    $file2 = preparationOfFiles($pathToFile2);
    $diff = [];

    foreach ($file1 as $key => $value) {
        if (array_key_exists($key, $file2) && $value === $file2[$key]) {
            $diff[$key] = ['value' => $value, 'status' => 'saved'];
        } elseif (array_key_exists($key, $file2) && $value !== $file2[$key]) {
            $diff[$key] = [
                'old value' => $value,
                'new value' => $file2[$key],
                'status' => 'modified'
            ];
        } elseif (!array_key_exists($key, $file2)) {
            $diff[$key] = ['value' => $value, 'status' => 'deleted'];
        }
    }

    foreach ($file2 as $key => $value) {
        if (!array_key_exists($key, $file1)) {
            $diff[$key] = ['value' => $value, 'status' => 'new'];
        }
    }

    ksort($diff);

    $result = [];
    foreach ($diff as $key => $value) {
        switch ($value) {
            case $value['status'] === 'saved':
                $result["  $key"] = $value['value'];
                break;
            case $value['status'] === 'modified':
                $result["- $key"] = $value['old value'];
                $result["+ $key"] = $value['new value'];
                break;
            case $value['status'] === 'deleted':
                $result["- $key"] = $value['value'];
                break;
            case $value['status'] === 'new':
                $result["+ $key"] = $value['value'];
                break;
        }
    }

    //return json_encode($diff, JSON_PRETTY_PRINT);
    return $result;
}
