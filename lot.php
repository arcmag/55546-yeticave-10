<?php

require_once 'init.php';

$connect = connect_db(DB_CONFIG);

if(is_user_authorization()) {
    $user = get_user_data($connect, $_SESSION['user_id']);
}

$categories = get_categories($connect);

$data_lot = get_lot_by_id($connect, $_GET['id']);

if($data_lot === -1) {
    http_response_code(404);
    $page_title = 'Ошибка 404: страница не найдена';
    $page_template = include_template('404.php');
} else {
    $page_title = $data_lot['name'];
    $page_template = include_template('lot.php', [
        'lot' => $data_lot,
        'wagers' => get_wagers_by_lot_id($connect, $data_lot['id'])
    ]);
}

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user,
]));
