<?php

require_once 'init.php';

$connect = connect_db(DB_CONFIG);

if (is_user_authorization()) {
    $user = get_user_data($connect, $_SESSION['user_id']);
}

$categories = get_categories($connect);
$category_id = $_GET['category'] ?? '';
$current_page = (int)($_GET['page'] ?? 1);
$category_idx = array_search($category_id, array_column($categories, 'id'));
$category_name = $categories[$category_idx]['name'];

$total = get_lot_by_category_result_count($connect, $category_id);
$max_page_result = 9;

$page_title = "Все лоты в категории: $category_name";
$page_template = include_template('lots-by-category.php', [
    'categories' => $categories,
    'announcement_list' => get_lots_by_category($connect, $category_id,
        $max_page_result, $current_page),
    'total' => $total,
    'max_page_result' => $max_page_result,
    'current_page' => $current_page,
    'count_pages' => ceil($total / $max_page_result),
    'category_id' => $category_id,
    'category_name' => $category_name,
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user,
]));
