<?php

namespace Differ\Differ;

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


use function Functional\if_else;

function genDiff($pathToFile1, $pathToFile2): array
{
    $file1 = json_decode(file_get_contents($pathToFile1, true), true);
    $file2 = json_decode(file_get_contents($pathToFile2, true), true);
    $diff = [];
    foreach ($file1 as $key1 => $item1) {
        foreach ($file2 as $key2 => $item2) {
            if ($key1 === $key2 && $item1 === $item2) {
                $diff[$key1] = $item1;
            } elseif ($key1 === $key2 && $item1 !== $item2) {
                $newKey1 = "- $key1";
                $newKey2 = "+ $key2";
                $diff[$newKey1] = $item1;
                $diff[$newKey2] = $item2;
            } elseif ($key1 !== $key2) {
                $newKey3 = "- $key1";
                $diff[$newKey3] = $item1;
            }

        }
    }
    return $diff;

//    return array_map(function ($item1, $item2) {
//        if ($item1 === $item2) {
//            return $item1;
//        }
//    }, $file1, $file2);
}

$pathToFile1 = 'file1.json';
$pathToFile2 = 'file2.json';

print_r(genDiff($pathToFile1, $pathToFile2));
