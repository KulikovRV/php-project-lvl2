<?php

namespace App\Formater\Stylish;

use function Functional\pick;

/**
 * @throws \JsonException
 */

// Добавить в diff новый статус 'root' для самого верхнего уровня
// после этого будет новый case в format

function stylish($diff)
{
    return iter($diff);
}

function iter($node, $intend = 1) : string
{
    $children = null;

    if (isset($node['status']) && $node['status'] === 'nested') {
        $children = pick($node, 'value');
    }
//    todo добавить отступы
    $space = ' ';

    $oldValue = pick($node, 'old value');
    $newValue = pick($node, 'new value');
    $savedValue = pick($node, 'value');

    switch ($node['status']) {
        case 'nested':
            $mapped = array_map(fn($child) => iter($child), $children);
            $result = implode("\n", $mapped);
            return "\n{$result}\n";
        case 'saved':
            $formattedValue = stringify($savedValue);
            return "$space {$node['key']}:  $formattedValue";
        case 'deleted':
            $formattedValue = stringify($savedValue);
            return "$space - {$node['key']}:  $formattedValue";
        case 'new':
            $formattedValue = stringify($savedValue);
            return "$space + {$node['key']}:  $formattedValue";
        case 'modified':
            $formattedValue1 = stringify($oldValue);
            $formattedValue2 = stringify($newValue);
            $lines =  [
                "{$space}- {$node['key']}: {$formattedValue1}",
                "{$space}+ {$node['key']}: {$formattedValue2}"
            ];
            return implode("\n", $lines);
        default:
            throw new \Exception("Unknown type: {$node['type']}");
    }
}

function stringify($diff, string $replacer = ' ', int $spacesCount = 4) : string
{
    if (is_bool($diff)) {
        return $diff ? 'true' : 'false';
    }

    if (is_null($diff)) {
        return 'null';
    }

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

function toString($value): string
{
    return trim(var_export($value, true), "'");
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

function addStatusView($diff)
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
                            $val['key'] => $iter($val['value'])
                        ];
                    case $val['status'] === 'saved':
                        return [
                            "  " . $val['key'] => $val['value']
                        ];
                    case $val['status'] === 'modified':
                        return [
                            "- " . $val['key'] => $val['old value'],
                            "+ " . $val['key'] => $val['new value']
                        ];
                    case $val['status'] === 'deleted':
                        return [
                            "- " . $val['key'] => $val['value']
                        ];
                    case $val['status'] === 'new':
                        return [
                            "+ " . $val['key'] => $val['value'],
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
