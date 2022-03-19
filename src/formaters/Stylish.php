<?php

namespace App\Formater\Stylish;

use function Functional\pick;

/**
 * @throws \JsonException
 */

function stylish($diff)
{
    return iter($diff);
}

function iter($node, $depth = 1) : string
{
    $children = null;

    if (isset($node['status']) && $node['status'] === 'nested') {
        $children = pick($node, 'value');
    }

//    todo добавить отступы
    $space = buildIndent($depth);

    $oldValue = pick($node, 'old value');
    $newValue = pick($node, 'new value');
    $savedValue = pick($node, 'value');

    switch ($node['status']) {
        case 'nested':
            $mapped = array_map(fn($child) => iter($child, $depth + 1), $children);
            $result = implode("\n", $mapped);
            if (isset($node['key'])) {
                return "$space {$node['key']}: {\n{$result}\n$space}";
            }
            return "{\n$space {$result}\n}";
        case 'saved':
            $formattedValue = stringify($savedValue);
            return "$space   {$node['key']}: $formattedValue";
        case 'deleted':
            $formattedValue = stringify($savedValue);
            return "$space - {$node['key']}: $formattedValue";
        case 'new':
            $formattedValue = stringify($savedValue);
            return "$space + {$node['key']}: $formattedValue";
        case 'modified':
            $formattedValue1 = stringify($oldValue);
            $formattedValue2 = stringify($newValue);
            $lines =  [
                "$space - {$node['key']}: {$formattedValue1}",
                "$space + {$node['key']}: {$formattedValue2}"
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
//        $closeBracketIndent = buildIndent($depth);
//        $keys = array_keys(get_object_vars($value));
//        $data = array_map(function ($key) use ($value, $depth): string {
//            $dataIndent = buildIndent($depth + 1);
//            $formattedValue = stringify($value->$key, $depth + 1);
//            return "{$dataIndent}  {$key}: {$formattedValue}";
//        }, $keys);
//        $indentSize = $depth * $spacesCount;
//        $currentIndent = str_repeat($replacer, $indentSize);
//        $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);

        $bracketIndent = buildIndent($depth);
        $currentIndent = buildIndent($depth + 1);

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

function buildIndent(int $depth, int $spacesCount = 4): string
{
    return str_repeat(' ', $depth * $spacesCount - 2);
}

//function addStatusView($diff)
//{
//    $iter = function ($currentValue) use (&$iter) {
//        if (!is_array($currentValue)) {
//            return $currentValue;
//        }
//
//        $lines = array_map(
//            function ($key, $val) use ($iter) {
//                switch ($val) {
//                    case $val['status'] === 'nested':
//                        return [
//                            $val['key'] => $iter($val['value'])
//                        ];
//                    case $val['status'] === 'saved':
//                        return [
//                            "  " . $val['key'] => $val['value']
//                        ];
//                    case $val['status'] === 'modified':
//                        return [
//                            "- " . $val['key'] => $val['old value'],
//                            "+ " . $val['key'] => $val['new value']
//                        ];
//                    case $val['status'] === 'deleted':
//                        return [
//                            "- " . $val['key'] => $val['value']
//                        ];
//                    case $val['status'] === 'new':
//                        return [
//                            "+ " . $val['key'] => $val['value'],
//                        ];
//                }
//            },
//            array_keys($currentValue),
//            $currentValue
//        );
//        return $lines;
//    };
//
//    return $iter($diff);
//}
