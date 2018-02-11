<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 06.02.2018
 * Time: 23:08
 */
class Wer
{
    public $name;

    public function show()
    {
        echo $this->name;
    }
}

$one = new Wer;
//$one->name = 456;
$one->show();