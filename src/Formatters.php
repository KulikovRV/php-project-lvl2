<?php


namespace App\Formatters;

use function App\Formater\Stylish\stylish;

function getFormatter($diff, $format)
{
    switch ($format) {
        case 'stylish':
            return stylish($diff);
        default:
            throw new \Exception("Invalid output format {$format}");
    }
}