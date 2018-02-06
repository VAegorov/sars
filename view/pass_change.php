<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06.02.2018
 * Time: 20:35
 */
?>
<p><a href="index.php">Вернуться на главную</a></p>
<h2>Смена пароля</h2>

<form action="index.php" method="POST">
    <p>Введите новый пароль <input type="password" name="new_password"></p>
    <p>Повторите новый пароль <input type="password" name="new_password2"></p>
    <p>Введите старый пароль <input type="password" name="old_password"></p>
    <input type="submit" name="pass_change">
</form>
