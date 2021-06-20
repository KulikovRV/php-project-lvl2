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


function genDiff($pathToFile1, $pathToFile2)
{
    $file1 = json_decode(file_get_contents($pathToFile1, true), true);
    $file2 = json_decode(file_get_contents($pathToFile2, true), true);
    $diff = [];

    foreach ($file1 as $key => $value) {
        if (array_key_exists($key, $file2) && $value === $file2[$key]) {
            $diff[$key] = $value;
        } elseif (array_key_exists($key, $file2) && $value !== $file2[$key]) {
            $diff["- $key"] = $value;
            $diff["+ $key"] = $file2[$key];
        } elseif (!array_key_exists($key, $file2)) {
            $diff["- $key"] = $value;
        }
    }

    foreach ($file2 as $key => $value) {
        if (!array_key_exists($key, $file1)) {
            $diff["+ $key"] = $value;
        }
    }
    //ksort($diff);
    return json_encode($diff, JSON_PRETTY_PRINT);
    //return $diff;
}

$pathToFile1 = 'file1.json';
$pathToFile2 = 'file2.json';

//print_r(genDiff($pathToFile1, $pathToFile2));
