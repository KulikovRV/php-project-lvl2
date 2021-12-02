<?php

namespace App\Formater\Stylish;

/**
 * @throws \JsonException
 */

function stylish($diff)
{
    $tree = iter($diff);
    return array_values($tree);
}

function iter($diff)
{
    $keys = array_keys($diff);
    $keys2 = array_flip($keys);
    $result = array_map(function ($value_keys2) use ($diff, $keys) {
        $key = $keys[$value_keys2];
        $value = $diff[$key] ?? null;

        switch ($value) {
            case $value['status'] === 'nested':
                return iter($value['value']);
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
//        return json_encode($result, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
}
