<?php

namespace App\Formatters;

use function App\Formater\Json\renderJson;
use function App\Formater\Stylish\stylish;
use function App\Formater\Plain\plain;

function getFormatter(array $diff, string $format): string
{
    switch ($format) {
        case 'stylish':
            return stylish($diff);
        case 'plain':
            return plain($diff);
        case 'json':
            return  renderJson($diff);
        default:
            throw new \Exception("Invalid output format {$format}");
    }
}
