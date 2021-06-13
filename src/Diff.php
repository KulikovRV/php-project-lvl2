<?php

namespace Differ\Differ;

function genDiff($pathToFile1, $pathToFile2)
{
    $j1 = file_get_contents($pathToFile1, true);
    $j2 = file_get_contents($pathToFile2, true);
    print_r($j1);
    print_r($j2);
}

$pathToFile1 = 'file1.json';
$pathToFile2 = 'file2.json';

genDiff($pathToFile1, $pathToFile2);
