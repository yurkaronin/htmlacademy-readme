<?php

// Параметры для подключения к БД
$db = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'root',
    'database' => 'readme',
];

// Соединение с БД
$db_connection = mysqli_connect($db['host'],
    $db['user'],
    $db['password'],
    $db['database']
);

// Обработка ошибки подключения
if (!$db_connection) {
    echo mysqli_connect_error();
    exit();
}

// Установка кодировки для работы с БД
mysqli_set_charset($db_connection, 'utf8');
