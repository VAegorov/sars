<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 08.02.2018
 * Time: 14:13
 */
?>

<p><a href="index.php">Вернуться на главную</a></p>
<h1>Страница администратора</h1>

<form id="fo" action="index.php" method="POST"></form>
<form id="ed" action="index.php" method="GET"></form>
<table border="1" cellpadding="8" bordercolor="#92A319" width="100%" cellspacing="0">
    <tr>
        <th>id</th>
        <th>логин</th>
        <th>email</th>
        <th>статус</th>
        <th>удалить</th>
        <th>забанить</th>
        <th>редактировать</th>
    </tr>
    <?php
        foreach ($users as $user)://а если переменная пустая или её нет
    ?>
    <tr>
        <td><?=$user['id']; ?></td>
        <td><?=$user['login']; ?></td>
        <td><?=$user['email']; ?></td>
        <td><?=$user['status']; ?></td>
        <td><button form="fo" type="submit" name="del_admin" value="<?=$user['id']; ?>">Удалить</button></td>
        <td>
            <button <?=$user['ban']; ?> form="fo" type="submit" name="ban_admin" value="<?=$user['id']; ?>">Забанить</button>
            <button <?=$user['outban']; ?> form="fo" type="submit" name="outban_admin" value="<?=$user['id']; ?>">Разбанить</button>
        </td>
        <td>
            <button form="ed" type="submit" name="id" value="<?=$user['id']; ?>">Редактировать</button>
            <input form="ed" name="edit_page" value="on" type="text" hidden>
        </td>
    </tr>
    <?php
    endforeach;
    ?>
</table>
