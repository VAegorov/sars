<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06.02.2018
 * Time: 18:57
 */
?>

<p><a href="index.php">Вернуться на главную</a></p>
<h2 aling="center">Список пользователей</h2>

<div>
    <table border="1" cellpadding="8" bordercolor="red" width="100%" cellspacing="0">
        <tr>
            <th></th>
            <th>Логин</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>email</th>
            <th>Отправить сообщение</th>
        </tr>
        <?php
        foreach ($users as $elem):
            //foreach ($elem as $u):
            ?>
            <tr>
                <td><?=$i++; ?></td>
                <td><?=$elem['login']; ?></td>
                <td><?=$elem['name']; ?></td>
                <td><?=$elem['surname']; ?></td>
                <td><?=$elem['email']; ?></td>
                <td><a href="index.php?message=on&id=<?=$elem['id']; ?>">Отправить сообщение</a></td>
            </tr>
        <?php
        endforeach;
        ?>
    </table>
</div>

