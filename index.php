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


$status_arr = [2, 10];

//если поступила форма бана от администратора
if (!empty($_POST['ban_admin'])) {
    $query = sprintf("UPDATE sars SET banned=1 WHERE id=%d", (int) $_POST['ban_admin']);
    $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
    if (mysqli_affected_rows($link) == 1) {
        echo "Пользователь забанен.";
        $_GET['admin_page'] = 'on';
    } else {
        echo "Неудача, попробуйте попозже.";
        $_GET['admin_page'] = 'on';
    }
}

//если поступила форма разбана от администратора
if (!empty($_POST['outban_admin'])) {
    $query = sprintf("UPDATE sars SET banned=0 WHERE id=%d", (int) $_POST['outban_admin']);
    $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
    if (mysqli_affected_rows($link) == 1) {
        echo "Пользователь разабанен.";
        $_GET['admin_page'] = 'on';
    } else {
        echo "Неудача, попробуйте попозже.";
        $_GET['admin_page'] = 'on';
    }
}

//если поступила форма об отправлении сообщения
if (isset($_POST['mess'])) {
    if (!empty($_POST['message_t'])) {
        if (trim($_POST['message_t'])) {
            //отправляем сообщение
            $message = mysqli_real_escape_string($link, trim($_POST['message_t']));
            $query = sprintf("INSERT INTO sars_mess (recipient_id, sender_id, message) VALUES (%d, %d, '%s')",
                $_POST['id'], $_SESSION['id'], $message);
            $result = mysqli_query($link, $query) or die(mysqli_error($link) . "Ошибка обработки запроса.");

            if (mysqli_affected_rows($link) == 1) {
                echo "Сообщение отправлено";
            } else {
                echo "Системная ошибка. Сообщение не отправлено";
            }
        } else {
           echo "Сообщение пустое, не отправлено.";
           $_GET['id'] = $_POST['id'];
           $_GET['message'] = 'on';
        }
    } else {
        echo "Сообщение пустое, не отправлено.";
        $_GET['id'] = $_POST['id'];
        $_GET['message'] = 'on';
    }
}

//если поступила форма удаления аккаунта некоего пользователя от администратора
if (!empty($_POST['del_admin'])) {
    $query = sprintf("DELETE FROM sars WHERE id=%d", (int) $_POST['del_admin']);
    $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
    if (mysqli_affected_rows($link) == 1) {
        echo "Аккаунт удален успешно.";
        $_GET['admin_page'] = 'on';
    } else {
        echo "Аккаунт не удален, попробуйте попозже.";
        $_GET['admin_page'] = 'on';
    }
}

//если поступила форма на удаление аккаунта
if (isset($_POST['delete'])) {
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($link, trim($_POST['password']));
        $query = sprintf("SELECT salt, password FROM sars WHERE id=%d", (int) $_SESSION['id']);
        $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
        $user = mysqli_fetch_assoc($result);
        if ($user['password'] === salt(trim($_POST['password']), $user['salt'])) {
            //Пароль совпал, удаляем аккаунт
            $query = sprintf("DELETE FROM sars WHERE id=%d", (int) $_SESSION['id']);
            $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
            if (mysqli_affected_rows($link) == 1) {
                echo "Аккаунт удален успешно.";
                $_GET['out_page'] = 'on';
            }
        } else {
            echo "Вы ввели неправильный пароль.";
            $_GET['delete_page'] = 'on';
        }
    }
}


//если посступила форма редактирования
if (isset($_POST['edit'])) {
    if (!empty($_POST['name']) && !empty($_POST['surname']) && !empty($_POST['email'])) {
        //проверяем не занят ли вводимый email
        $new_email = mysqli_real_escape_string($link, trim($_POST['email']));
        $query = sprintf("SELECT id FROM sars WHERE email='%s'", $new_email);
        $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
        $user = mysqli_fetch_assoc($result);
        if (!$user || ($user['id']) == $_SESSION['id']) {
            //редактируем профиль
            $name = mysqli_real_escape_string($link, trim($_POST['name']));
            $surname = mysqli_real_escape_string($link, trim($_POST['surname']));
            $query = sprintf("UPDATE sars SET name='%s', surname='%s', email='%s' WHERE id=%d", $name, $surname, $new_email, $_SESSION['id']);
            $result = mysqli_query($link, $query) or die(mysqli_error($link) . "Ошибка обработки запроса.1");
            if (mysqli_affected_rows($link) == 1) {
                echo "Изменения успешно внесены.";
            } elseif (mysqli_affected_rows($link) == 0) {
                echo "Вы ничего не изменили.";
            }
        } else {
            $ed = 1;
            $_GET['edit_page'] = 'on';
            echo "Введенный email занят, введите другой.";
        }
    } else {
        $ed = 1;
        $_GET['edit_page'] = 'on';
        echo "Вы не заполнили все поля!";
    }
}

//если поступила форма с данными нового пароля пользователя
if (isset($_POST['pass_change'])) {
    if (!empty($_POST['new_password']) && !empty($_POST['new_password2']) && !empty($_POST['old_password'])) {
    $new_password = trim($_POST['new_password']);
    $new_password2 = trim($_POST['new_password2']);
    $old_password = trim($_POST['old_password']);
    if ($new_password !== $new_password2) {
        echo "Введите два одинаковых значения паспорта!";
        include "view/pass_change.php";
    } else {
        //продолжаем
        $query = sprintf("SELECT password, salt FROM sars WHERE id='%d'", $_SESSION['id']);
        $result = mysqli_query($link, $query) or die('Ошибка обработки запроса.');
        $user = mysqli_fetch_assoc($result);
        if ($user['password'] === salt($old_password, $user['salt'])) {

            $salt = generatorSalt();
            $password = mysqli_real_escape_string($link, salt($new_password, $salt));
            $query = sprintf("UPDATE sars SET password='%s', salt='%s' WHERE id=%d", $password, $salt, $_SESSION['id']);
            $result = mysqli_query($link, $query) or die('Ошибка обработки запроса.');
            if (mysqli_affected_rows($link) == 1) {
                echo "Пароль сменен успешно";
            }
        } else {
            echo "Старый пароль введен неверно.";
            include "view/pass_change.php";
        }
    }
    } else {
        echo "Вы не заполнили все поля!";
        include "view/pass_change.php";
    }
}

//Если поступил запрос на выход
if (!empty($_GET['out_page']) && $_GET['out_page'] === 'on') {
    $_SESSION = [];
    $_COOKIE = [];
    //если этого нет, то основная cookie(session_name) до закрытия браузера никогда не уберется
    setcookie(session_name(), '', time(), '/');
    session_destroy();
}

//если поступила форма авторизации
if (isset($_POST['auto_f'])) {
    if (!empty($_POST['auto_f']) && !empty($_POST['login']) && !empty($_POST['password'])) {
        $login = mysqli_real_escape_string($link, trim($_POST['login']));
        $query = sprintf("SELECT * FROM sars WHERE login='%s' AND banned!=1", $login);
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            $password = trim($_POST['password']);
            if ($user['password'] === salt($password, $user['salt'])) {
                //пользователь с такими данными есть, авторизуем его
                $_SESSION['login'] = $user['login'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['status'] = $user['status'];
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
            $query = sprintf("INSERT INTO sars (name, surname, login, password, salt, email, status) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', 1)", $name, $surname, $login, $password, $salt, $email);
            $result = mysqli_query($link, $query) or die(mysqli_error($link));
            if (mysqli_affected_rows($link) == 1) {
                echo "Регистрация прошла успешно.";
                //авторизуем пользователя через сессию
                $_SESSION['login'] = $login;
                $_SESSION['id'] = mysqli_insert_id($link);
                $_SESSION['status'] = 1;
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
    if (isAccess($status_arr)) {
        echo '<p><a href="index.php?admin_page=on">Вход в админку</a></p>';
    }
    include "view/auth_page.php";
} elseif (!empty($_COOKIE['login']) && !empty($_COOKIE['ikey'])){
    //проверяем авторизацию по cookie
    $login = mysqli_real_escape_string($link, $_COOKIE['login']);
    $ikey = mysqli_real_escape_string($link, $_COOKIE['ikey']);
    $query = sprintf("SELECT * FROM sars WHERE login='%s' AND ikey='%s' AND banned!=1", $login, $ikey);
    $result = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
        //в cookie логин и ikey совпали
        $_SESSION['login'] = $user['login'];
        $_SESSION['id'] = $user['id'];
        $_SESSION['status'] = $user['status'];
        $_SESSION['auth'] = true;
        //пользователь авторизован по cookie и сведения записаны в сессию
        //подключаем шаблон страницы
        include "view/index_v.php";
        if (isAccess($status_arr)) {
            echo '<p><a href="index.php?admin_page=on">Вход в админку</a></p>';
        }
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

//если поступил запрос на просмотр списка пользователей
if (!empty($_GET['users_page']) && $_GET['users_page'] === 'on') {
    $query = "SELECT * FROM sars";
    $result = mysqli_query($link, $query) or die("Ошибка при обработке запроса");
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row ;
    }
    $i = 1;
    include "view/list_users.php";
}

//поступил запрос о смене пароля
if (!empty($_GET['pass_page']) && $_GET['pass_page'] === 'on') {
    include "view/pass_change.php";
}

//если поступил запрос на редактирование
if (!empty($_GET['edit_page']) && $_GET['edit_page'] === 'on') {
    if (!empty($ed) && $ed === 1) {
        $user['name'] = $_POST['name'];
        $user['surname'] = $_POST['surname'];
        $user['email'] = $_POST['email'];
    } else {
        $query = sprintf("SELECT email, name, surname FROM sars WHERE id=%d", $_SESSION['id']);
        $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
        $user = mysqli_fetch_assoc($result) or die("Ошибка обработки запроса.");
    }

    include "view/edit.php";
}

//если поступил запрос на удаление аккаунта
if (!empty($_GET['delete_page']) && $_GET['delete_page'] === 'on') {
    include "view/delit_v.php";
}

//если поступил запрос на отправку сообщения
if (!empty($_GET['message']) && $_GET['message'] === 'on') {
    include "view/message.php";
}

//если поступил запрос на удаление сообщения
if (!empty($_POST['del_mes'])) {
    $query = sprintf("DELETE FROM sars_mess WHERE id=%d", $_POST['del_mes']);
    $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
    if (mysqli_affected_rows($link)) {
        echo "Сообщение удалено.";
        $_GET['lk_page'] = 'on';
    } else {
        echo "Сообщение не удалено, попробуйте позже.";
        $_GET['lk_page'] = 'on';
    }
}

//если поступил запрос о входе в ЛК
if (!empty($_GET['lk_page']) && $_GET['lk_page'] === 'on') {
    $query = sprintf("SELECT * FROM sars_mess WHERE recipient_id=%d", $_SESSION['id']);
    $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
    if (mysqli_affected_rows($link) >= 1) {
        $i = 1;
        $message = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $message[] = $row;
        }
        include "view/lk.php";
    } else {
        echo "Новых сообщений нет";
    }
}

//если поступил запрос на прочтение одного сообщения
if (!empty($_POST['one_mes'])) {
    $query = sprintf("SELECT * FROM sars_mess WHERE id=%d", $_POST['one_mes']);
    $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
    $message_one = mysqli_fetch_assoc($result);
    $query = sprintf("UPDATE sars_mess SET read_s='прочитано' WHERE id=%d", $_POST['one_mes']);
    $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
    include "view/messsage_one.php";
}

//если поступил запрос на вход в админку
if (!empty($_GET['admin_page']) && $_GET['admin_page'] === 'on') {
    $query = "SELECT * FROM sars";
    $result = mysqli_query($link, $query) or die("Ошибка обработки запроса.");
    $users = [];
    $ban = '';
    $outban = '';
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['banned'] == 1) {
            $row['ban'] = 'hidden';
            $row['outban'] = '';
        } else {
            $row['ban'] = '';
            $row['outban'] = 'hidden';
        }
        $users[] = $row;
    }
    include "view/admin_page.php";
}
