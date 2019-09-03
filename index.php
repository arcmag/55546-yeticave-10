<?php

require_once 'init.php';

$connect = connect_db(DB_CONFIG);

if(is_user_authorization()) {
    $user = get_user_data($connect, $_SESSION['user_id']);
}

$categories = get_categories($connect);

$page = $_GET['page'];

$page_title = 'Главная страница';
$page_template = include_template('main.php', [
    'categories' => $categories,
    'announcement_list' => get_announcement_list($connect)
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user,
]));
