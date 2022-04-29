<?php

namespace App\Parser;

use Exception;
use Symfony\Component\Yaml\Yaml;

function preparationOfFiles(string $pathToFile)
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    $content = file_get_contents($pathToFile, true);

    if ($content === false) {
        throw new Exception("Cannot access '$pathToFile' to read contents.");
    }

    $data = '';

    return match ($extension) {
        'json' => json_decode($content, associative: true),
        'yml', 'yaml' => Yaml::parse($content),
        default => $data,
    };
}
