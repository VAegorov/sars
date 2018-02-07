<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 07.02.2018
 * Time: 18:48
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
    <p><a href="index.php">Вернуться на главную</a></p>
    <h2>Личный кабинет</h2>
    <table border="1" cellpadding="8" bordercolor="red" width="100%" cellspacing="0">
        <tr>
            <th></th>
            <th>Отправитель</th>
            <th>Дата</th>
            <th>Сообщение</th>
            <th>Отметка о прочтении</th>
        </tr>
    <?php
    foreach ($message as $mes):
    ?>

        <tr>
            <td><?=$i++; ?></td>
            <td><?=$mes['sender_id']; ?></td>
            <td><?=$mes['date']; ?></td>
            <td><?=$mes['message']; ?></td>
            <td><?=$mes['read_s']; ?></td>
        </tr>

    <?php
    endforeach;
    ?>

    </table>
</body>
</html>



