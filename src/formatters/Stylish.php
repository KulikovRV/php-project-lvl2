<?php

namespace App\Formater\Stylish;

use function Functional\pick;

/**
 * @throws \JsonException
 */

function stylish(array $diff)
{
    return iter($diff);
}

function iter(array $node, int $depth = 1): string
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
            $formattedValue = stringify($savedValue, $depth);
            return "$space  {$node['key']}: $formattedValue";
        case 'deleted':
            $formattedValue = stringify($savedValue, $depth);
            return "$space- {$node['key']}: $formattedValue";
        case 'new':
            $formattedValue = stringify($savedValue, $depth);
            return "$space+ {$node['key']}: $formattedValue";
        case 'modified':
            $formattedValue1 = stringify($oldValue, $depth);
            $formattedValue2 = stringify($newValue, $depth);

            if ($formattedValue1 === '') {
                $lines =  [
                    "$space- {$node['key']}: {$formattedValue1}",
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

    if (!is_array($value)) {
        return toString($value);
    }

    $closeBracketIndent = buildIndent($depth);
    $keys = array_keys($value);
    $data = array_map(function ($key) use ($value, $depth): string {
        $dataIndent = buildIndent($depth + 1);
        $formattedValue = stringify($value[$key], $depth + 1);
        return "{$dataIndent}  {$key}: {$formattedValue}";
    }, $keys);
    $string = implode("\n", $data);
    $result = "{\n{$string}\n{$closeBracketIndent}  }";
    return $result;
}

function toString(mixed $value): string
{
    return trim(var_export($value, true), "'");
}

function buildIndent(int $depth, int $spacesCount = 4): string
{
    return str_repeat(' ', $depth * $spacesCount - 2);
}
