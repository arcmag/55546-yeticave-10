<?php

require_once 'init.php';

$connect = connect_db(DB_CONFIG);

if (is_user_authorization()) {
    $user = get_user_data($connect, $_SESSION['user_id']);
}

$categories = get_categories($connect);

$max_page_result = 9;
$current_page = (int)htmlspecialchars($_GET['page'] ?? 1);

$search = trim(htmlspecialchars($_GET['search'] ?? ''));
$total = get_search_result_count($connect, $search);
$result = get_search_result($connect, $search, $max_page_result, $current_page);

$count_pages = ceil($total / $max_page_result);

$page_title = "Результаты поиска по запросу «{$search}»";
$page_template = include_template('search.php', [
    'search' => $search,
    'result' => $result,
    'current_page' => $current_page,
    'total' => $total,
    'max_page_result' => $max_page_result,
    'count_pages' => $count_pages,
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user,
    'search' => $search,
]));
