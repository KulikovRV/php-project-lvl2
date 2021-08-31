<?php

namespace App\Parser;

use Symfony\Component\Yaml\Yaml;

function preparationOfFiles($pathToFile) : array
{
    $pathPartsFile = pathinfo($pathToFile);
    $extension = $pathPartsFile['extension'];
    $data = [];

    switch ($extension) {
        case $extension === 'json':
            $data = json_decode(file_get_contents($pathToFile, true), true, 512, JSON_THROW_ON_ERROR);
            break;
        case $extension === 'yml':
        case $extension === 'yaml':
            $data = Yaml::parseFile($pathToFile);
            break;
    }

    return $data;
}
