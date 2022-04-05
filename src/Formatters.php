<?php


namespace App\Formatters;

use function App\Formater\Stylish\stylish;
use function App\Formater\Plain\plain;

function getFormatter($diff, $format)
{
    switch ($format) {
        case 'stylish':
            return stylish($diff);
        case 'plain':
            return plain($diff);
        default:
            throw new \Exception("Invalid output format {$format}");
    }
}