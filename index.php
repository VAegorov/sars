<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 05.02.2018
 * Time: 19:08
 */

require_once 'model/helper.php';
$link = connectBD();
session_start();

//проверяем авторизацию по сессии
if (!empty($_SESSION['auth']) && $_SESSION['auth'] === true) {
    //пользователь авторизован по сессии
    //подключаем шаблон страницы и в дальнейшем также поступаем для других случаев авторизации др способом

} elseif (!empty($_COOKIE['login']) && !empty($_COOKIE['ikey'])){
    //проверяем авторизацию по cookie
    $login = mysqli_real_escape_string($link, $_COOKIE['login']);
    $ikey = mysqli_real_escape_string($link, $_COOKIE['ikey']);
    $query = sprintf("SELECT * FROM sars WHERE login='%s' and ikey='%s'", $login, $ikey);
    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        //в cookie логин и ikey совпали
        $_SESSION['login'] = $user['login'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['auth'] = true;
        //пользователь авторизован по cookie и сведения записаны в сессию
        //подключаем шаблон страницы
    } else {
        //пользователь никак не авторизован, отображаем страницу для него
        include "view/no_auth_page.php";
        exit();
    }
} else {
    //пользователь никак не авторизован, отображаем страницу для него
    include "view/no_auth_page.php";
    exit();
}

echo "Страница index.php.<br>";