<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06.02.2018
 * Time: 22:11
 */
?>
<p><a href="index.php">Вернуться на главную</a></p>
<h1 align="center">Редактирование профиля</h1>

<form action="index.php" method="POST">

    <p><input type="text" name="name" value="<?=$user['name']; ?>"></p>
    <p><input type="text" name="surname" value="<?=$user['surname']; ?>"></p>
    <p><input type="text" name="email" value="<?=$user['email']; ?>"></p>
    <input type="submit" name="edit">


</form>
