<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 07.02.2018
 * Time: 17:31
 */
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

</body>
</html>
<p><a href="index.php">Вернуться на главную</a></p>

<form action="index.php" method="POST">
    <p>Введите сообщение <textarea name="message_t" rows="5" cols="100"></textarea></p>
    <input type="text" name="id" value="<?=$_GET['id']; ?>" hidden>
    <input type="submit" name="mess">
</form>