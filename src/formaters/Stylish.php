<?php

namespace App\Formater\Stylish;

use function Functional\flatten;

/**
 * @throws \JsonException
 */

// Добавить в diff новый статус 'root' для самого верхнего уровня
// после этого будет новый case в format

function stylish($diff)
{
    $treeWitStatus = addStatusView($diff);
    ksort($treeWitStatus);
    $unpackedTree = unPacking1($treeWitStatus);
    $resultingTree = [];
    unPacking2($unpackedTree, $resultingTree);
//    var_dump(convertArrayToString($resultingTree));
    return convertArrayToString($resultingTree);
}

function unPacking1($array)
{
    $result = [];
    $iter = function ($item) use (&$iter, &$result) {
        if (!is_array($item)) {
            return $item;
        }

        foreach ($item as $key => $value) {
            if (!is_int($key)) {
                $result[$key] = $value;
                continue;
            }
            if (is_array($value)) {
                $iter($value);
            }
        }
    };
    $iter($array);
    return $result;
}

function unPacking2($array, &$acc, $prevKey = null)
{
    if (!is_array($array)) {
        return;
    }
    foreach ($array as $key => $value) {
        if (is_numeric($key)) {
            if (array_key_exists($prevKey, $acc)) {
                $res = array_merge($acc[$prevKey], $value);
            } else {
                $res = $value;
            }
            $acc[$prevKey] = $res;
        }
        unPacking2($value, $acc, $key);
    }
}

function addStatusView($diff) : array
{
    $iter = function ($currentValue) use (&$iter) {
        if (!is_array($currentValue)) {
            return $currentValue;
        }

        $lines = array_map(
            function ($key, $val) use ($iter) {
                switch ($val) {
                    case $val['status'] === 'nested':
                        return [
                            "$key" => $iter($val['value'])
                        ];
                    case $val['status'] === 'saved':
                        return [
                            "  $key" => $val['value']
                        ];
                    case $val['status'] === 'modified':
                        return [
                            "- $key" => $val['old value'],
                            "+ $key" => $val['new value']
                        ];
                    case $val['status'] === 'deleted':
                        return [
                            "- $key" => $val['value']
                        ];
                    case $val['status'] === 'new':
                        return [
                            "+ $key" => $val['value'],
                        ];
                }
            },
            array_keys($currentValue),
            $currentValue
        );
        return $lines;
    };

    return $iter($diff);
}

function toString($value): string
{
    return trim(var_export($value, true), "'");
}

function convertArrayToString($diff, string $replacer = ' ', int $spacesCount = 4) : string
{
    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $indentSize = $depth * $spacesCount;
        $currentIndent = str_repeat($replacer, $indentSize);
        $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);

        $lines = array_map(
            fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $depth + 1)}",
            array_keys($currentValue),
            $currentValue
        );

        $result = ['{', ...$lines, "{$bracketIndent}}"];
        return implode("\n", $result);
    };

    return $iter($diff, 1);
}
