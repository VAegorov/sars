<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06.02.2018
 * Time: 18:57
 */
?>

<h2>Список пользователей</h2>

<div>
    <table>
        <tr>
            <th>hhhh</th><th>Логин</th>
        </tr>
        <?php
        foreach ($users as $elem):
            //foreach ($elem as $u):
            ?>
            <tr>
                <td><?=$i++; ?></td><td><?=$elem['login']; ?></td>
            </tr>



        <?php
        endforeach;
        //endforeach;
        ?>
    </table>
</div>
