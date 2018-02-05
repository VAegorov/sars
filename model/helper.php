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

//генератор соли
function generatorSalt()
{
    $saltlehgth = 8;
    $salt = '';
    for ($i = 0; $i < $saltlehgth; $i++) {
        $salt .= chr(mt_rand(33, 126));
    }
    return $salt;
}

//возвращает соленый пароль
function salt($password, $salt)
{
    return md5($password . $salt);
}

