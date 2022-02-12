<?php

namespace App\Formater\Stylish;

/**
 * @throws \JsonException
 */

function stylish($diff)
{
    $tree = format($diff);
    return $tree;
}

function toString($value)
{
    return trim(var_export($value, true), "'");
}

// В format надо использовать обход похожий на iter3
// Использовать тот же вариант с замыканием iter, только сделать поправку на отступы, они не нужны
// Задача состоит в добавлении + или - ключам, так же можно сразу сделать вид key => value
// реализацию key => value мы можем сделать и в iter3
// когда format будет отрабатывать, мы его запихнем в stylish и будем отдавать iter3 вместо diff
// уже iter3 будет делать строку со всеми отступами
// внутри замыкания iter нужен switch
// сначала попробовать на плоской структуре, потом уже вложенной
// написать тесты для плоской, а потом вложенной структуры
// посмотреть свой тест StylishTest.php
// обход в глубину в iter3 нужен для отступов, в format он не пригодиться


function format($diff) : array
{
    $iter = function ($currentValue) use (&$iter) {
        if (!is_array($currentValue)) {
            // тут switch ?
            return $currentValue;
        }


    };

    return $iter($diff);
}

function iter3($diff, string $replacer = ' ', int $spacesCount = 4) : string
{
    // замыкание
    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue)) {
            // тут switch ?
            return toString($currentValue);
        }

        // в format это не надо
        $indentSize = $depth * $spacesCount;
        $currentIndent = str_repeat($replacer, $indentSize);
        $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);

        // кажется оно нужно для format
//        $lines = array_map(
//            fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $depth + 1)}",
//            array_keys($currentValue),
//            $currentValue
//        );


        $lines = array_map(function ($key, $val) use ($depth, $iter, $currentIndent, $currentValue) {
            if ($key === 'status') {
                switch ($key) {
                    case $val === 'deleted':
                        return "{$currentIndent}'- '{$key}:{$iter($currentValue['value'], $depth + 1)}";
                }

            }
            return "{$currentIndent}{$key}: {$iter($val, $depth + 1)}";
            }, array_keys($currentValue), $currentValue);

        $result = ['{', ...$lines, "{$bracketIndent}}"];
        return implode("\n", $result);
    };

    return $iter($diff, 1);
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
