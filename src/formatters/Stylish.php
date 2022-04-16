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

function iter($node, $depth = 1): string
{
    $children = pick($node, 'value');

    $space = buildIndent($depth);

    $oldValue = pick($node, 'old value');
    $newValue = pick($node, 'new value');
    $savedValue = pick($node, 'value');

    switch ($node['status']) {
        case 'root':
            $mapped = array_map(
                fn($child) => iter($child, $depth),
                $children
            );
            $result = implode("\n", $mapped);
            return "{\n{$result}\n}";
        case 'nested':
            $mapped = array_map(
                fn($child) => iter($child, $depth + 1),
                $children
            );
            $result = implode("\n", $mapped);
            return "{$space}  ${node['key']}: {\n{$result}\n{$space}  }";
        case 'saved':
            $formattedValue = stringify($savedValue);
            return "$space  {$node['key']}: $formattedValue";
        case 'deleted':
            $formattedValue = stringify($savedValue);
            return "$space- {$node['key']}: $formattedValue";
        case 'new':
            $formattedValue = stringify($savedValue, 4);
            return "$space+ {$node['key']}: $formattedValue";
        case 'modified':
            $formattedValue1 = stringify($oldValue);
            $formattedValue2 = stringify($newValue);
            var_dump($oldValue);
            if ($formattedValue1 === '') {
                $lines =  [
                    "$space- {$node['key']}:{$formattedValue1}",
                    "$space+ {$node['key']}: {$formattedValue2}"
                ];
            } else {
                $lines =  [
                    "$space- {$node['key']}: {$formattedValue1}",
                    "$space+ {$node['key']}: {$formattedValue2}"
                ];
            }

            return implode("\n", $lines);
        default:
            throw new \Exception("Unknown type: {$node['status']}");
    }
}

function stringify(mixed $value, int $depth = 1): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_array($value)) {
        var_dump($value);
        return implode(' ', $value);
    }

    if (!is_object($value)) {
        return (string) $value;
    }

    $closeBracketIndent = buildIndent($depth);
    $keys = array_keys(get_object_vars($value));
    $data = array_map(function ($key) use ($value, $depth): string {
        $dataIndent = buildIndent($depth + 1);
        $formattedValue = stringify($value->$key, $depth + 1);
        return "{$dataIndent}  {$key}: {$formattedValue}";
    }, $keys);
    $string = implode("\n", $data);
    $result = "{\n{$string}\n{$closeBracketIndent}  }";
    return $result;
}

function stringify2($diff): string
{
    if (is_bool($diff)) {
        return $diff ? 'true' : 'false';
    }

    if (is_null($diff)) {
        return 'null';
    }

    $iter = function ($currentValue, $depth) use (&$iter) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $bracketIndent = buildIndent($depth);
        $currentIndent = buildIndent($depth + 1);

        $lines = array_map(
            fn($key, $val) => "  {$currentIndent}{$key}: {$iter($val, $depth + 1)}",
            array_keys($currentValue),
            $currentValue
        );

        $result = ['{', ...$lines, "{$bracketIndent}  }"];
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
