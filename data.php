<?php

function get_categories() {
    return [
        'boards' => 'Доски и лыжи',
        'attachment' => 'Крепления',
        'boots' => 'Ботинки',
        'clothing' => 'Одежда',
        'tools' => 'Инструменты',
        'other' => 'Разное'
    ];
}

function get_announcement_list() {
    $categories = get_categories();

    return [
        [
            'name' => '2014 Rossignol District Snowboard',
            'category' => $categories['boards'],
            'price' => 10999,
            'img' => 'img/lot-1.jpg',
            'expiration_date' => '01.10.2019 17:00:00',
        ],
        [
            'name' => 'DC Ply Mens 2016/2017 Snowboard',
            'category' => $categories['boards'],
            'price' => 159999,
            'img' => 'img/lot-2.jpg',
            'expiration_date' => '01.10.2019',
        ],
        [
            'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
            'category' => $categories['attachment'],
            'price' => 8000,
            'img' => 'img/lot-3.jpg',
            'expiration_date' => '01.10.2019',
        ],
        [
            'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
            'category' => $categories['boots'],
            'price' => 10999,
            'img' => 'img/lot-4.jpg',
            'expiration_date' => '01.10.2019',
        ],
        [
            'name' => 'Куртка для сноуборда DC Mutiny Charocal',
            'category' => $categories['clothing'],
            'price' => 7500,
            'img' => 'img/lot-5.jpg',
            'expiration_date' => '01.10.2019',
        ],
        [
            'name' => 'Маска Oakley Canopy',
            'category' => $categories['other'],
            'price' => 5400,
            'img' => 'img/lot-6.jpg',
            'expiration_date' => '01.10.2019',
        ],
    ];
}