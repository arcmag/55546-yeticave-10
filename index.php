<?php

require_once 'config/db.php';

require_once './helpers.php';
require_once './data.php';
require_once './functions.php';

$user_name = 'Николай';

$connect = connect_db(DB_CONFIG);

$categories = get_categories($connect);

$main_page = include_template('main.php', [
    'categories' => $categories,
    'announcement_list' => get_announcement_list($connect)
]);

print(include_template('layout.php', [
    'title' => 'Главная страница',
    'is_auth' => rand(0, 1),
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $main_page
]));
