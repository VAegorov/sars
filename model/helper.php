<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 05.02.2018
 * Time: 20:22
 */

function connectBD()
{
    $link = mysqli_connect('localhost', 'root', '', 'test');
    mysqli_set_charset($link, 'utf-8');
    return $link;
}

