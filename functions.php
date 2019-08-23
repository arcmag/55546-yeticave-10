<?php

function debug($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

function to_format_currency($price) {
    return number_format($price, 0, '', ' ');
}

function get_dt_range($date) {
    return explode(':', date('H:i', strtotime($date) - time()));
}

function connect_db($config) {
    $connect = mysqli_connect($config['HOST'], $config['USER'], $config['PASSWORD'], $config['DB'])
        or die('Ошибка подключения ' . mysqli_connect_error());
    mysqli_set_charset($connect, 'utf-8');

    return $connect;
}
