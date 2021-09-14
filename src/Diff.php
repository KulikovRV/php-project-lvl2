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
// [proxy=> [value => 123...., status => deleted]] ; вот такой пример если?)

/*
function downcaseFileNames($tree)
{
    $name = getName($tree);
    $newMeta = getMeta($tree);

    if (isFile($tree)) {
        return mkfile(strtolower(getName($tree)), $newMeta);
    }

    $children = getChildren($tree);
    $newChildren = array_map(fn($child) => downcaseFileNames($child), $children);
    $newTree = mkdir($name, $newChildren, $newMeta);

    return $newTree;
}
 */

function genDiff($file1, $file2): array
{
    $diff = [];

    foreach ($file2 as $key => $value) {
        if (!array_key_exists($key, $file1)) {
            $diff[$key] = [
                'value' => $value,
                'status' => 'new'
            ];
        }
    }

    if (is_array($file1)) {
        foreach ($file1 as $key => $value) {
            if (is_array($key)) {
                return genDiff($key, $file2);
            }

            $isKeyExistsInFile2 = array_key_exists($key, $file2);
            if ($isKeyExistsInFile2 && $value === $file2[$key]) {
                $diff[$key] = [
                    'type' => 'children',
                    'value' => $value,
                    'status' => 'saved'
                ];
            } elseif ($isKeyExistsInFile2 && $value !== $file2[$key]) {
                $diff[$key] = [
                    'type' => 'children',
                    'old value' => $value,
                    'new value' => $file2[$key],
                    'status' => 'modified'
                ];
            } elseif (!$isKeyExistsInFile2) {
                $diff[$key] = [
                    'type' => 'children',
                    'value' => $value,
                    'status' => 'deleted'
                ];
            }
        }
    }
    return $diff;
}


/*
function genDiff($file1, $file2): array
{
    $diff = [];
    foreach ($file1 as $key => $value) {
        $isKeyExistsInFile2 = array_key_exists($key, $file2);

        if ($isKeyExistsInFile2 && $value === $file2[$key]) {
            $diff[$key] = [
                'value' => $value,
                'status' => 'saved'
            ];
        } elseif ($isKeyExistsInFile2 && $value !== $file2[$key]) {
            $diff[$key] = [
                'old value' => $value,
                'new value' => $file2[$key],
                'status' => 'modified'
            ];
        } elseif (!$isKeyExistsInFile2) {
            $diff[$key] = [
                'value' => $value,
                'status' => 'deleted'
            ];
        }
    }

    foreach ($file2 as $key => $value) {
        if (!array_key_exists($key, $file1)) {
            $diff[$key] = [
                'value' => $value,
                'status' => 'new'
            ];
        }
    }
    ksort($diff);
    //return json_encode($diff, JSON_PRETTY_PRINT);
    return $diff;
}
*/
