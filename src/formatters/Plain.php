<?php
namespace App\Formater\Plain;

function plain($diff)
{
    return 1;
    return implode("\n", iter($diff, ''));
}

function iter($node)
{

}