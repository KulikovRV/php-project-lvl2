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


function genDiff($array1, $array2, $diff = []): array
{
    $keys = array_keys(get_object_vars($array2));

//    if (property_exists($array1, $key))

    if (is_array($array2)) {
        foreach ($array2 as $key2 => $value2) {
            if (array_key_exists($key2, $array1)) {
                $diff[$key2] = [
                    'type' => 'node',
                    'status' => 'saved',
                    'value' => $value2,
                    'children' => genDiff($array1[$key2], $value2, $diff),
                ];
                //var_dump($array1[$key2]);
                //var_dump($value2);
                $a= 1;
            }

            $diff[$key2] = [
                'type' => 'node',
                'status' => 'new',
                'value' => $value2
            ];

            /*if (!array_key_exists($key2, $array1)) {
                $diff[$key2] = [
                    'type' => 'node',
                    'status' => 'new',
                    'value' => $value2
                ];
            }*/
        }
    }

    if (is_array($array1)) {
        foreach ($array1 as $key1 => $value1) {
            if (!array_key_exists($key1, $array2)) {
                $diff[$key1] = [
                    'type' => 'node',
                    'status' => 'deleted',
                    'value' => $value1
                ];
            }
        }
    }

    ksort($diff);

    return $diff;
}
