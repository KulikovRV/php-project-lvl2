<?php

namespace App\Parser;

use Symfony\Component\Yaml\Yaml;

function preparationOfFiles(string $pathToFile)
{
    $pathPartsFile = pathinfo($pathToFile);
    $extension = $pathPartsFile['extension'];
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
