<?php

function get_categories($connect) {
    $result = mysqli_query($connect, 'SELECT * FROM `category`')
        or die('Ошибка при обращении к базе данных: ' . mysqli_error());

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_announcement_list($connect) {
    $result = mysqli_query($connect, "
        SELECT l.id, l.name as name, l.start_price, l.img, c.name as category, l.date_end
        FROM `lot` `l`
        LEFT JOIN `category` `c` ON c.id = l.category_id
        WHERE l.date_end > NOW()
        ORDER BY l.date_create DESC")
        or die('Ошибка при обращении к базе данных: ' . mysqli_error());

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_lot_by_id($connect, $lot_id) {
    $stmt = mysqli_prepare($connect, "
        SELECT l.*, c.name as category
        FROM `lot` `l`
        LEFT JOIN `category` `c` ON c.id = l.category_id
        WHERE l.id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?? -1;
}

function get_wagers_by_lot_id($connect, $lot_id) {

    return mysqli_fetch_all(mysqli_query(
        $connect, "SELECT w.*, u.name as author FROM `wager` `w` LEFT JOIN `user` `u` ON u.id = w.author_id WHERE w.lot_id = $lot_id"),
        MYSQLI_ASSOC);
}
