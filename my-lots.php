<?php

require_once 'init.php';

$connect = connect_db(DB_CONFIG);

if (!is_user_authorization()) {
    header('Location: /login.php');
}

$categories = get_categories($connect);

$user = get_user_data($connect, $_SESSION['user_id']);
$wagers = get_wagers_by_user_id($connect, $_SESSION['user_id']);

$page_title = 'Список моих ставок';
$page_template = include_template('my-lots.php', [
    'connect' => $connect,
    'wagers' => $wagers,
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user,
]));
