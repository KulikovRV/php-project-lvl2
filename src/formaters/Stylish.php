<?php

namespace App\Formater\Stylish;

/**
 * @throws \JsonException
 */

// Добавить в diff новый статус 'root' для самого верхнего уровня
// после этого будет новый case в format

function stylish($diff)
{
    $tree = format($diff);
    var_dump($tree);
    $result = [];
    // Распаковка массива
    foreach ($tree as $key => $val) {
        foreach ($val as $key2 => $val2) {
            $result[$key2] = $val2;
        }
    }
    ksort($tree);
    return $result;

}

function toString($value): string
{
    return trim(var_export($value, true), "'");
}

function format($diff) : array
{
    $iter = function ($currentValue) use (&$iter) {
        if (!is_array($currentValue)) {
            return $currentValue;
        }

        $lines = array_map(
            function ($key, $val) use ($iter) {
                switch ($val) {
                    case $val['status'] === 'nested':
                        return $iter($val['value']);
                    case $val['status'] === 'saved':
                        return [
                            "  $key:" => $val['value']
                        ];
                    case $val['status'] === 'modified':
                        return [
                            "- $key:" => $val['old value'],
                            "+ $key:" => $val['new value']
                        ];
                    case $val['status'] === 'deleted':
                        return [
                            "- $key:" => $val['value']
                        ];
                    case $val['status'] === 'new':
                        return [
                            "+ $key:" => $val['value'],
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

function iter($diff, string $replacer = ' ', int $spacesCount = 4) : string
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


//        $lines = array_map(function ($key, $val) use ($depth, $iter, $currentIndent, $currentValue) {
//            if ($key === 'status') {
//                switch ($key) {
//                    case $val === 'deleted':
//                        return "{$currentIndent}'- '{$key}:{$iter($currentValue['value'], $depth + 1)}";
//                }
//
//            }
//            return "{$currentIndent}{$key}: {$iter($val, $depth + 1)}";
//            }, array_keys($currentValue), $currentValue);

        $result = ['{', ...$lines, "{$bracketIndent}}"];
        return implode("\n", $result);
    };

    return $iter($diff, 1);
}
