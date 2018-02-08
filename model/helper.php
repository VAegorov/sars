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
    mysqli_set_charset($link, 'utf8');
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

//определяет, является ли админом текущий пользователь
function isAdmin()
{
    if ($_SESSION['status'] == 10) {
        return true;
    } else return false;
}

//проверяет является ли текущий пользователь модератором или администратором и дает ему доступ в этом случае возвращает
// true, если текущему пользователю доступ разрешен и false — если запрещен. Принимает массив статусов с разрешенным
//доступом
function isAccess($status_arr)
{
    foreach ($status_arr as $status) {
       if ($status == $_SESSION['status']) {
           return true;
       }
    } return false;
}

//