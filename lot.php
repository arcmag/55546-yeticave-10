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

    $min_cost = empty($wagers) ? $data_lot['start_price'] :
        $wagers[0]['price'] + $data_lot['bid_step'];

    if (!empty($_POST)) {
        $cost = (int)$_POST['cost'];

        if ($cost >= $min_cost) {
            create_new_wager($connect, $data_lot['id'], $_SESSION['user_id'],
                $cost);
            $wagers = get_wagers_by_lot_id($connect, $data_lot['id']);
            $data_lot = get_lot_by_id($connect, $data_lot['id']);
        } else {
            $cost_error = "Минимально допустимая сумма ставки: {$min_cost}";
        }
    }

    $page_title = $data_lot['name'];
    $page_template = include_template('lot.php', [
        'id' => $_GET['id'],
        'lot' => $data_lot,
        'wagers' => $wagers,
        'cost_error' => $cost_error,
        'min_cost' => $min_cost
    ]);
}

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user
]));
