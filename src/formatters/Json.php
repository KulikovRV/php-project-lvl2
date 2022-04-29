<?php

namespace App\Formater\Json;

function renderJson(array $diff)
{
    return json_encode($diff);
}
