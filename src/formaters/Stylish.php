<?php

namespace App\Formater\Stylish;

use function Functional\flatten;

/**
 * @throws \JsonException
 */

function stylish($diff)
{
//    var_dump($diff);
//    exit();
    $tree = iter2($diff);
    return $tree;
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
//                    return iter($value2);
                      return iter($value);

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
    $result = array_map(function ($key, $value) {
        if (!is_array($value)) {
//            var_dump($value);
            return;
        }
        switch ($value) {
            case $value['status'] === 'nested':
                foreach ($value['value'] as $value2) {
//                    var_dump($key);
                    if ($key !== "common") {
//                        var_dump($key);
//                        var_dump($value2);
//                        exit();
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
                    "+ $key" => $value['value']
                ];
            case $value['status'] === 'deleted':
                return [
                    "- $key" => $value['value']
                ];
            case $value['status'] === 'new':
                return [
                    "+ $key" => $value['value'],
                ];
        }
    }, array_keys($diff), array_values($diff));
//    return $result;
    return json_encode($result, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
}
