<?php
$_fn=realpath(__DIR__."/../data")."/data.db";
return [
    'class' => 'yii\db\Connection',
    'dsn' => "sqlite:$_fn",
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
