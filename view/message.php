<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 07.02.2018
 * Time: 17:31
 */
?>

<p><a href="index.php">Вернуться на главную</a></p>

<form action="index.php" method="POST">
    <p>Введите сообщение <textarea name="message_t" rows="5" cols="100"></textarea></p>
    <input type="text" name="id" value="<?=$_GET['id']; ?>" hidden>
    <input type="submit" name="mess">
</form>