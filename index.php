<?php

require_once 'init.php';

$connect = connect_db(DB_CONFIG);

if (is_user_authorization()) {
    $user = get_user_data($connect, $_SESSION['user_id']);
}

update_status_lots($connect);

$categories = get_categories($connect);

$page_title = 'Главная страница';
$page_template = include_template('main.php', [
    'categories' => $categories,
    'announcement_list' => get_announcement_list($connect),
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
    'user' => $user,
]));

/*

    Минимальная длинна слова для поиска 3 символа!
    Поиск идёт по любым текстовым полям!

    Поиск лота по полям:
        Название
        Описание


    ---Базовые настройки для полнотекстового поиска

    CREATE FULLTEXT INDEX `other_name` ON lot(name, description)

    ---Пример натурального поиска

    SELECT * FROM lot WHERE MATCH(name, description) AGAINST('search_word')


    ---Получить при выборке все найденные позиции по релевантности

    SELECT name, description, MATCH(name, description) AGAINST('search_word') as score
    FROM gifs
    WHERE MATCH(name, description) AGAINST('search_word')

    ---Добавить стоп слова в полнотекстовый поиск

    1) Создать таблицу стоп слов с один полем value
    2) В настройках mysql установить опцию innodb_ft_server_stopword_table = 'db_name/table_name'

    ---Пример логического поиска
    (Не сортирует по релевантности!)

    SELECT * FROM lot WHERE MATCH(name, description) AGAINST ('search_word' IN BOOLEAN MODE)







       ---------------------------
    Реализация
        1) Выполнить запрос 1 раз! Можно в коде, можно через SQL в phpmyadmin
        1) mysqli_query($connect, "CREATE FULLTEXT INDEX lot_if_search ON lot(name, description)")





 * */
