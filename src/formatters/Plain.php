<?php

namespace App\Formater\Plain;

use function Functional\pick;

function plain($diff)
{
    return iter($diff);
}

function iter($node, $ansentry = '')
{
    $children = null;

    if (isset($node['status']) && in_array($node['status'], ['root', 'nested'])) {
        $children = pick($node, 'value');
    }

    $oldValue = pick($node, 'old value');
    $newValue = pick($node, 'new value');
    $savedValue = pick($node, 'value');

    switch ($node['status']) {
        case 'root':
            $mapped = array_map(
                fn($child) => iter($child),
                $children
            );
            $result = implode("\n", $mapped);
            return (string)($result);
        case 'nested':
            $mapped = array_map(
                fn($child) => iter($child, "$ansentry{$node['key']}."),
                $children
            );
            $filtered = array_filter($mapped, fn($item) => !is_null($item));
            return implode("\n", $filtered);
        case 'new':
            $formattedValue1 = stringify($savedValue);
            return "Property '$ansentry{$node['key']}' was added with value: {$formattedValue1}";
        case 'deleted':
            return  "Property '$ansentry{$node['key']}' was removed";
        case 'modified':
            $formattedValue2 = stringify($oldValue);
            $formattedValue3 = stringify($newValue);
            return "Property '$ansentry{$node['key']}' was updated. From {$formattedValue2} to {$formattedValue3}";
        case 'saved':
            break;
        default:
            throw new \Exception("Unknown type: {$node['status']}");
    }
}

function stringify($value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    return is_array($value) ? "[complex value]" : "'$value'";
}
