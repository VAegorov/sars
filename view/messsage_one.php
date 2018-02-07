<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 07.02.2018
 * Time: 22:24
 */
?>

<p><a href="index.php">Вернуться на главную</a></p>
<p><a href="index.php?lk_page=on">Назад к сообщениям</a></p>

<h2>Сообщение от <?=$message_one['sender_id']; ?></h2>
<p>Поступило: <?=nl2br($message_one['date']); ?></p>
<p><?=$message_one['message']; ?></p>
