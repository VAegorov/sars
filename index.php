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

//Если поступил запрос на выход
if (!empty($_GET['out']) && $_GET['out'] === 'on') {
    $_SESSION = [];
    $_COOKIE = [];
    //если этого нет основная cookie(session_name) до закрытия браузера никогда не уберется
    setcookie(session_name(), '', time(), '/');
    session_destroy();
}

//если поступила форма авторизации
if (isset($_POST['auto_f'])) {
    if(!empty($_POST['auto_f']) && !empty($_POST['login']) && !empty($_POST['password'])) {
        $login = mysqli_real_escape_string($link, trim($_POST['login']));
        $query = sprintf("SELECT * FROM sars WHERE login='%s'", $login);
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            $password = trim($_POST['password']);
            if ($user['password'] === salt($password, $user['salt'])) {
                //пользователь с такими данными есть, авторизуем его
                $_SESSION['login'] = $user['login'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['auth'] = true;
            } else {
                //если пользователя с такими данными нет
                $_GET['aut'] = 'on';
                echo "Неправильно указаны логин или пароль!";
            }
        } else {
            //если пользователя с таким логином данными
            $_GET['aut'] = 'on';
            echo "Неправильно указаны логин или пароль!";
        }
    } else {
        //не заполнены все поля
        $_GET['aut'] = 'on';
        echo "Не заполнены все поля!";
    }
}


//если поступила форма регистрации
if (isset($_POST['registr_f'])) {
    //по правильному надо обрезовать концевые пробелы
    if (!empty($_POST['registr_f']) && !empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['email']) && !empty($_POST['login']) && !empty($_POST['password'])) {
        //проверяем логин и email на незанятость
        $login = mysqli_real_escape_string($link, trim($_POST['login']));
        $email = mysqli_real_escape_string($link, trim($_POST['email']));
        $query = sprintf("SELECT * FROM sars WHERE login='%s' OR email='%s'", $login, $email);
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
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
            $name = mysqli_real_escape_string($link, trim($_POST['name']));
            $surname = mysqli_real_escape_string($link, trim($_POST['surname']));
            $salt = generatorSalt();
            $password = salt(trim($_POST['password']), $salt);
            $password = mysqli_real_escape_string($link, $password);
            $salt = mysqli_real_escape_string($link, $salt);
            $query = sprintf("INSERT INTO sars (name, surname, login, password, salt, email) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $name, $surname, $login, $password, $salt, $email);
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            if (mysqli_affected_rows($link) === 1) {
                echo "Регистрация прошла успешно.";
                //авторизуем пользователя через сессию
                $_SESSION['login'] = $login;
                $_SESSION['id'] = mysqli_insert_id($link);
                $_SESSION['auth'] = true;
            }
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
    include "view/index_v.php";
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
        include "view/auth_page.php";
    } else {
        //пользователь никак не авторизован, отображаем страницу для него
        include "view/index_v.php";
        //если поступил запрос на регистрацию
        if (!empty($_GET['registr']) && $_GET['registr'] === 'on') {
            //выводим форму для регистрации
            include "view/form.php";
            //если поступил запрос о входе
        } elseif (!empty($_GET['aut']) && $_GET['aut'] === 'on') {
            //выводим форму для входа
            include "view/auth_form.php";
        } else include "view/no_auth_page.php";

    }
} else {
    //пользователь никак не авторизован, отображаем страницу для него
    include "view/index_v.php";
    //если поступил запрос на регистрацию
    if (!empty($_GET['registr']) && $_GET['registr'] === 'on') {
        //выводим форму для регистрации
        include "view/form.php";
        //если поступил запрос о входе
    } elseif (!empty($_GET['aut']) && $_GET['aut'] === 'on') {
        //выводим форму для входа
        include "view/auth_form.php";
    } else include "view/no_auth_page.php";

}