<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 07.02.2018
 * Time: 18:48
 */
?>


    <p><a href="index.php">Вернуться на главную</a></p>
    <h2>Личный кабинет</h2>
    <form id="one" action="index.php" method="POST"></form>
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
            <td><?=$mes['message']; ?>
                <button form="one" type="submit" name="one_mes" value="<?=$mes['id']; ?>">Прочитать</button>
                <button form="one" type="submit" name="del_mes" value="<?=$mes['id']; ?>">Удалить</button>
            </td>
            <td><?=$mes['read_s']; ?></td>
        </tr>

    <?php
    endforeach;
    ?>

    </table>




