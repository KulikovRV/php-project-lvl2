<?php

namespace App\Formater\Stylish;

/**
 * @throws \JsonException
 */

function stylish($diff)
{
//    $result = [];
//    foreach ($diff as $key => $value) {
//        switch ($value) {
//            case $value['status'] === 'saved':
//                $result["  $key"] = $value['value'];
//                break;
//            case $value['status'] === 'modified':
//                $result["- $key"] = $value['old value'];
//                $result["+ $key"] = $value['new value'];
//                break;
//            case $value['status'] === 'deleted':
//                $result["- $key"] = $value['value'];
//                break;
//            case $value['status'] === 'new':
//                $result["+ $key"] = $value['value'];
//                break;
//        }
//    }

    $keys = array_keys($diff);
    ksort($keys);
    $keys2 = array_flip($keys);
    $result = array_map(function ($key1) use ($diff, $keys, $keys2) {
        $key2 = $keys[$key1];
        $value = $diff[$key2] ?? null;

        switch ($value) {
            case $value['status'] === 'nested':
                return stylish($value['value']);
//                return [
//                    "  $key2" => stylish($value['value'])
//                ];
            case $value['status'] === 'saved':
                return [
                    "  $key2" => $value['value']
                ];
            case $value['status'] === 'modified':
                return [
                    "- $key2" => $value['old value'],
                    "+ $key2" => $value['new value']
                ];
            case $value['status'] === 'deleted':
//                return $value['value'];
                return [
                    "- $key2" => $value['value']
                ];
            case $value['status'] === 'new':
//                return $value['value'];
                return [
                    "+ $key2" => $value['value'],
                ];
        }
    }, $keys2);
//    return $result;
        return json_encode($result, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
}