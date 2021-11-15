<?php

namespace App\Formater\Stylish;

/**
 * @throws \JsonException
 */

function stylish($diff): bool|string
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
    $result = array_map(function ($key) use ($diff, $keys) {
        $key = $keys[$key];
        $value = $diff[$key] ?? null;

        switch ($value) {
            case $value['status'] === 'nested':
                return [
//                    "  $key" => stylish($value['value'])
                    "  $key" => stylish($value['value'])
                ];
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
                return [
                    "- $key" => $value['value']
                ];
            case $value['status'] === 'new':
                return [
                    "+ $key" => $value['value'],
                ];
        }
    }, $keys2);

        return json_encode($result, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
}