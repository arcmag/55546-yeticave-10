<?php

require_once 'init.php';

$categories = get_categories($connect);

$lot_id = !empty($_GET['id']) ? (int)$_GET['id'] : -1;
$data_lot = get_lot_by_id($connect, $lot_id);

if (empty($data_lot)) {
    http_response_code(404);
    $page_title = 'Ошибка 404: страница не найдена';
    $page_template = include_template('404.php');
} else {
    $wagers = get_wagers_by_lot_id($connect, $data_lot['id']);
    $cost_error = null;

    $min_cost = empty($wagers)
        ? $data_lot['start_price']
        :
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
        'id' => $lot_id,
        'lot' => $data_lot,
        'wagers' => $wagers,
        'cost_error' => $cost_error,
        'min_cost' => $min_cost,
    ]);
}

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user,
]));
