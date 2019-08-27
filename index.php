<?php

require_once 'config/global.php';
require_once 'config/db.php';

require_once './helpers.php';
require_once './data.php';
require_once './functions.php';

$user_name = 'Николай';

$connect = connect_db(DB_CONFIG);

$categories = get_categories($connect);

$page = $_GET['page'];

debug($categories, false);

if(isset($page)) {
    if($page === 'lot') {
        $data_lot = get_lot_by_id($connect, $_GET['lot_id']);

        debug($data_lot, false);

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
    } else if($page === 'add_lot') {
        $page_title = 'Добавление лота';
        $page_template = include_template('add.php', [
            'categories' => $categories,
        ]);
    }
} else {
    $page_title = 'Главная страница';
    $page_template = include_template('main.php', [
        'categories' => $categories,
        'announcement_list' => get_announcement_list($connect)
    ]);
}

print(include_template('layout.php', [
    'title' => $page_title,
    'is_auth' => rand(0, 1),
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $page_template
]));
