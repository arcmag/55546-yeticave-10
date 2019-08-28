<?php

require_once 'config/global.php';
require_once 'config/db.php';

require_once './helpers.php';
require_once './data.php';
require_once './functions.php';

$user_name = 'Николай';

$connect = connect_db(DB_CONFIG);

$categories = get_categories($connect);

$page_title = 'Добавление лота';
$page_template = include_template('add.php', [
    'categories' => $categories,
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'is_auth' => rand(0, 1),
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $page_template
]));
