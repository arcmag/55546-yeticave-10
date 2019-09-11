<?php

require_once 'init.php';

$connect = connect_db(DB_CONFIG);

if (is_user_authorization()) {
    $user = get_user_data($connect, $_SESSION['user_id']);
}

$categories = get_categories($connect);
$data_lot = get_lot_by_id($connect, $_GET['id']);

if ($data_lot === -1) {
    http_response_code(404);
    $page_title = 'Ошибка 404: страница не найдена';
    $page_template = include_template('404.php');
} else {
    $wagers = get_wagers_by_lot_id($connect, $data_lot['id']);
    $cost_error = null;

    if (!empty($_POST)) {
        $cost = (int)$_POST['cost'];

        $current_price = $wagers[0]['price'] ?? $data_lot['start_price'];

        if ($cost >= $current_price + $data_lot['bid_step']) {
            create_new_wager($connect, $data_lot['id'], $_SESSION['user_id'],
                $cost);
            $wagers = get_wagers_by_lot_id($connect, $data_lot['id']);
            $data_lot = get_lot_by_id($connect, $data_lot['id']);
        } else {
            $cost_error
                = "Цена ставки должна быть выше текущей цены {$current_price} 
                    + определённый шаг ставки {$data_lot['bid_step']}";
        }
    }

    $page_title = $data_lot['name'];
    $page_template = include_template('lot.php', [
        'id' => $_GET['id'],
        'lot' => $data_lot,
        'wagers' => $wagers,
        'cost_error' => $cost_error,
    ]);
}

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user,
]));
