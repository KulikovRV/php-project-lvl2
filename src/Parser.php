<?php

namespace App\Parser;

use Symfony\Component\Yaml\Yaml;

function preparationOfFiles(string $pathToFile)
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    $content = file_get_contents($pathToFile, true);
    $data = '';

    return match ($extension) {
        'json' => json_decode($content, associative: true),
        'yml', 'yaml' => Yaml::parse($content),
        default => $data,
    };
}
