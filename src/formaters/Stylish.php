<?php

namespace App\Formater\Stylish;

/**
 * @throws \JsonException
 */

function stylish($diff)
{
    $tree = iter2($diff);
    return $tree;
}

function iter($diff)
{
    var_dump($diff);
    $keys = array_keys($diff);
    $keys2 = array_flip($keys);
    $result = array_map(function ($value_keys2) use ($diff, $keys) {
        $key = $keys[$value_keys2];
        $value = $diff[$key] ?? null;
        switch ($value) {
            case $value['status'] === 'nested':
                foreach ($value['value'] as $value2) {
                    var_dump($value2);
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
                return [
                    "- $key" => $value['value']
                ];
            case $value['status'] === 'new':
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
            return;
        }
        switch ($value) {
            case $value['status'] === 'nested':
                foreach ($value['value'] as $value2) {
                    if ($key !== "common") {
                        return iter2($value2);
                    }
                }
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
