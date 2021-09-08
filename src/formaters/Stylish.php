<?php

namespace App\Formater\Stylish;

function stylish($diff)
{
    $result = [];
    foreach ($diff as $key => $value) {
        switch ($value) {
            case $value['status'] === 'saved':
                $result["  $key"] = $value['value'];
                break;
            case $value['status'] === 'modified':
                $result["- $key"] = $value['old value'];
                $result["+ $key"] = $value['new value'];
                break;
            case $value['status'] === 'deleted':
                $result["- $key"] = $value['value'];
                break;
            case $value['status'] === 'new':
                $result["+ $key"] = $value['value'];
                break;
        }
    }
        return $result;
}