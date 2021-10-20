<?php

namespace App\Differ;

use http\Header\Parser;
use function App\Parser\preparationOfFiles;

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

/*
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
*/

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
            return $diff[$key2] = [
                'status' => 'new',
                'value' => $value2
                //'value' => iter($value2, $value2, $diff)
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
    var_dump($diff);
    return $diff;
}

