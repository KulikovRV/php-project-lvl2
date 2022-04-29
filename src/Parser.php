<?php

namespace App\Parser;

use Symfony\Component\Yaml\Yaml;

function preparationOfFiles(string $pathToFile)
{
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);
    $content = file_get_contents($pathToFile, true);
    $data = '';

    switch ($extension) {
        case $extension === 'json':
            return json_decode($content, associative: true);
        case $extension === 'yml':
        case $extension === 'yaml':
            return Yaml::parse($content);
    }

    return $data;
}
