<?php

namespace App\Parser;

use Symfony\Component\Yaml\Yaml;

function preparationOfFiles($pathToFile)
{
    $pathPartsFile = pathinfo($pathToFile);
    $extension = $pathPartsFile['extension'];
    $content = file_get_contents($pathToFile, true);
    //$data = [];

    switch ($extension) {
        case $extension === 'json':
            $data = json_decode($content);
            break;
        case $extension === 'yml':
        case $extension === 'yaml':
            $data = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
    }

    return $data;
}
