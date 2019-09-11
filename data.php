<?php

/**
 * Получает из базы данных список со всеми категориями сайта
 *
 * @param object $connect объект соединения с базой данных
 *
 * @return array mysqli_fetch_all ассоциативный массив со списком всех категорий сайта
 */
function get_categories($connect)
{
    $result = mysqli_query($connect, 'SELECT * FROM `category`')
    or die('Ошибка при обращении к базе данных: '.mysqli_error());

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получает из базы данных список со всеми активными лотами
 *
 * @param object $connect объект соединения с базой данных
 *
 * @return array mysqli_fetch_all ассоциативный массив со списком активных лотов
 */
function get_announcement_list($connect)
{
    $result = mysqli_query($connect, "
        SELECT l.id, l.name as name, l.start_price, l.img, c.name as category, l.date_end
        FROM `lot` `l`
        LEFT JOIN `category` `c` ON c.id = l.category_id
        WHERE l.date_end > NOW()
        ORDER BY l.date_create DESC")
    or die('Ошибка при обращении к базе данных: '.mysqli_error());

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получает из базы данных нужный лот по переданному идентификатору
 *
 * @param object $connect объект соединения с базой данных
 * @param string $lot_id  строка с идентификатором лота
 *
 * @return array mysqli_fetch_assoc ассоциативный массив с данными нужного лота
 */
function get_lot_by_id($connect, $lot_id)
{
    $stmt = mysqli_prepare($connect, "
        SELECT l.*, c.name as category, MAX(w.price) as max_wager
        FROM `lot` `l`
        JOIN `category` `c` ON c.id = l.category_id
        JOIN `wager` `w` ON w.lot_id = l.id
        WHERE l.id = ?
        ");
    mysqli_stmt_bind_param($stmt, 's', $lot_id);
    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?? -1;
}

/**
 * Получает из базы данных список ставок для лота с указанным идентификатором
 *
 * @param object $connect объект соединения с базой данных
 * @param string $lot_id  строка с идентификатором лота
 *
 * @return array mysqli_fetch_all ассоциативный массив со списком ставок для данного лота
 */
function get_wagers_by_lot_id($connect, $lot_id)
{
    return mysqli_fetch_all(mysqli_query(
        $connect,
        "SELECT w.*, u.name as author
            FROM `wager` `w`
            LEFT JOIN `user` `u` ON u.id = w.author_id
            WHERE w.lot_id = $lot_id ORDER BY w.date DESC"),
        MYSQLI_ASSOC);
}

/**
 * Получает из базы данных список ставок сделанных пользователем с указанным идентификатором
 *
 * @param object $connect объект соединения с базой данных
 * @param string $user_id строка с идентификатором пользователя
 *
 * @return array mysqli_fetch_all ассоциативный массив со списком ставок от данного пользователя
 */
function get_wagers_by_user_id($connect, $user_id)
{
    $sql = "SELECT NOW() - (w.date - 0) as `date`, 
        MAX(w.price) as `price`, 
        l.id as lot_id , 
        l.img as `img`, 
        l.name as `name`, 
        l.date_end, 
        l.winner_id, 
        c.name as `cat_name`
        FROM `wager` `w`
        JOIN `lot` `l` ON l.id = w.lot_id
        JOIN `category` `c` ON c.id = l.category_id
        WHERE w.author_id = '$user_id'
        GROUP BY lot_id
        ORDER BY w.date DESC";

    return mysqli_fetch_all(mysqli_query($connect, $sql), MYSQLI_ASSOC);
}
