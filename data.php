<?php

function get_categories($connect) {
    $result = mysqli_query($connect, 'SELECT * FROM `category`')
        or die('Ошибка при обращении к базе данных: ' . mysqli_error());

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_announcement_list($connect) {
    $result = mysqli_query($connect, "
        SELECT l.name as name, l.start_price, l.img, c.name as category, w.price, l.date_end
        FROM `lot` `l`
        LEFT JOIN `category` `c` ON c.id = l.category_id
        LEFT JOIN `wager` `w` ON w.lot_id = l.id
        WHERE l.date_end > NOW()
        ORDER BY l.date_create DESC")
        or die('Ошибка при обращении к базе данных: ' . mysqli_error());

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
