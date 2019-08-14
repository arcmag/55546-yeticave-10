<?php

require_once './helpers.php';

function to_format_currency($price) {
    return number_format($price, 0, '', ' ');
}

$user_name = 'Николай';

$categories = [
    'boards' => 'Доски и лыжи',
    'attachment' => 'Крепления',
    'boots' => 'Ботинки',
    'clothing' => 'Одежда',
    'tools' => 'Инструменты',
    'other' => 'Разное'
];

$announcement_list = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => $categories['boards'],
        'price' => 10999,
        'img' => 'img/lot-1.jpg',
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories['boards'],
        'price' => 159999,
        'img' => 'img/lot-2.jpg',
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories['attachment'],
        'price' => 8000,
        'img' => 'img/lot-3.jpg',
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories['boots'],
        'price' => 10999,
        'img' => 'img/lot-4.jpg',
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories['clothing'],
        'price' => 7500,
        'img' => 'img/lot-5.jpg',
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => $categories['other'],
        'price' => 5400,
        'img' => 'img/lot-6.jpg',
    ],
];

$main_page = include_template('main.php', [
    'categories' => $categories,
    'announcement_list' => $announcement_list
]);

print(include_template('layout.php', [
    'title' => 'Главная страница',
    'is_auth' => rand(0, 1),
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $main_page
]));

?>
