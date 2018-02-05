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

//если поступила форма регистрации
if (isset($_POST['registr_f'])) {
    if (!empty($_POST['registr_f']) && !empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['email']) && !empty($_POST['login']) && !empty($_POST['password'])) {
        //проверяем логин и email на незанятость
        $login = mysqli_real_escape_string($link, $_POST['login']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $query = sprintf("SELECT * FROM sars WHERE login='%s' OR email='%s'", $login, $email);
        $result = mysqli_query($link, $query);
        $result_arr = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $result_arr[] = $row;
        }
        if ($result_arr) {
            //логин или email заняты
            $_GET['registr'] = 'on';
            echo "Указанные логин или email заняты, попробуйте другие!";
        } else {
            //вносим данные регистрации в БД
            echo "Будем регистрировать";
        }
    } else {
        //не заполнены все поля
        $_GET['registr'] = 'on';
        echo "Не заполнены все поля!";
    }
}


//проверяем авторизацию по сессии
if (!empty($_SESSION['auth']) && $_SESSION['auth'] === true) {
    //пользователь авторизован по сессии
    //подключаем шаблон страницы и в дальнейшем также поступаем для других случаев авторизации др способом
    include "view/auth_page.php";
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
        include "view/index_v.php";
    } else {
        //пользователь никак не авторизован, отображаем страницу для него
        include "view/index_v.php";
        //если поступил запрос на регистрацию
        if (!empty($_GET['registr']) && $_GET['registr'] === 'on') {
            //выводим форму для регистрации
            include "view/form.php";
        } else include "view/no_auth_page.php";
        exit();
    }
} else {
    //пользователь никак не авторизован, отображаем страницу для него
    include "view/index_v.php";
    if (!empty($_GET['registr']) && $_GET['registr'] === 'on') {
        //выводим форму для регистрации
        include "view/form.php";
    } else include "view/no_auth_page.php";
    exit();
}

echo "Страница index.php.<br>";