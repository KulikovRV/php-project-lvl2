<?php

namespace App\Formater\Stylish;

use function Functional\flatten;

/**
 * @throws \JsonException
 */

function stylish($diff)
{
    $tree = iter2($diff);
//    $result = [];
////    var_dump($tree);
//    foreach ($tree as $key => $value) {
//        if (is_array($value)) {
//            foreach ($value as $key1 => $value2) {
////                var_dump($key);
////                var_dump(array_keys($value2));
//                $newKey = array_keys($value2);
//                var_dump($value2);
//                $result[$key][$newKey[0]] = array_values($value2);
//            }
//        } else {
//            $result[$key] = $value;
//        }
//    }
      return $tree;
//    return flatten($tree);
}

function iter($diff)
{
    var_dump($diff);
    $keys = array_keys($diff);
//    var_dump($keys);
    $keys2 = array_flip($keys);
//    var_dump($keys2);
    $result = array_map(function ($value_keys2) use ($diff, $keys) {
//        var_dump($value_keys2);
        $key = $keys[$value_keys2];
        $value = $diff[$key] ?? null;
//        var_dump($key);
//        var_dump($diff);
        switch ($value) {
            case $value['status'] === 'nested':
                foreach ($value['value'] as $value2) {
                    var_dump($value2);
                    return iter($value2);
                }
//                return iter($value['value']);
//                return [
//                    "  $key2" => stylish($value['value'])
//                ];
            case $value['status'] === 'saved':
                return [
                    "  $key" => $value['value']
                ];
            case $value['status'] === 'modified':
                return [
                    "- $key" => $value['old value'],
                    "+ $key" => $value['new value']
                ];
            case $value['status'] === 'deleted':
//                $keys2["- $key"] = $keys2[$key];
//                unset($keys2[$key]);
//                return $value['value'];

                return [
                    "- $key" => $value['value']
                ];
            case $value['status'] === 'new':
//                return $value['value'];
                return [
                    "+ $key" => $value['value'],
                ];
        }
    }, $keys2);
    return $result;
//    return json_encode($result, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
}

function iter2($diff)
{
//    var_dump($diff);
//    $keys = array_keys($diff);
//    var_dump($keys);
//    $keys2 = array_flip($keys);
//    var_dump($keys2);
    $result = array_map(function ($key, $value) {
//        var_dump($key);
//        var_dump($value);
//        var_dump($value_keys2);
//        $key = $keys[$value];
//        $value = $diff[$key] ?? null;
//        var_dump($key);
//        var_dump($diff);
//        var_dump($value);
        if (!is_array($value)) {
            return;
        }
        switch ($value) {
            case $value['status'] === 'nested':
                foreach ($value['value'] as $value2) {
//                    var_dump($key);
                    if ($key !== "common") {
//                        var_dump($key);
//                        var_dump($value);
                        return iter2($value2);
                    }
//                    return iter2($value);
                }
//                return iter($value['value']);
//                return [
//                    "  $key2" => stylish($value['value'])
//                ];
            case $value['status'] === 'saved':
                return [
                    "  $key" => $value['value']
                ];
            case $value['status'] === 'modified':
                return [
                    "- $key" => $value['old value'],
                    "+ $key" => $value['new value']
                ];
            case $value['status'] === 'deleted':
//                $keys2["- $key"] = $keys2[$key];
//                unset($keys2[$key]);
//                return $value['value'];

                return [
                    "- $key" => $value['value']
                ];
            case $value['status'] === 'new':
//                return $value['value'];
                return [
                    "+ $key" => $value['value'],
                ];
        }
    }, array_keys($diff), array_values($diff));
//    return $result;
    return json_encode($result, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
}
