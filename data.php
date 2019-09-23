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
        SELECT
        l.id,
        l.name as name,
        l.start_price,
        l.img,
        c.name as category,
        l.date_end, 
        l.date_end - NOW() as rest_time
        FROM `lot` `l`
        LEFT JOIN `category` `c` ON c.id = l.category_id
        WHERE l.date_end > NOW()
        ORDER BY l.date_create DESC")
    or die('Ошибка при обращении к базе данных: '.mysqli_error());

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает найденные записи лотов для указанной категории
 *
 * @param object  $connect         объект соединения с базой данных
 * @param integer $category_id     идентификатор категории по которой будет идти поиск
 * @param integer $max_page_result максмимальное количество результатов на странице
 * @param integer $page            число с текущей страницей
 *
 * @return array mysqli_fetch_all ассоциативный массив со списком активных лотов выбранной категории
 */
function get_lots_by_category($connect, $category_id, $max_page_result, $page)
{
    $category_id = mysqli_real_escape_string($connect, $category_id);
    $max_page = mysqli_real_escape_string($connect, $max_page_result);
    $page = mysqli_real_escape_string($connect, $page);

    $offset = ($page - 1) * $max_page;
    $count = mysqli_query($connect, "SELECT * FROM `category`
        WHERE id = '$category_id'");

    $sql = "
    SELECT l.id, l.name as name, l.start_price, l.img, c.name as category, l.date_end
    FROM `lot` `l`
    LEFT JOIN `category` `c` ON c.id = l.category_id
    WHERE l.date_end > NOW() ";

    if (mysqli_num_rows($count) === 1) {
        $sql .= "AND c.id = '$category_id' ";
    }

    $sql .= "ORDER BY l.date_create DESC LIMIT $offset, $max_page_result";
    $res = mysqli_query($connect, $sql);

    return mysqli_fetch_all($res, MYSQLI_ASSOC);
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
    $lot_id = mysqli_real_escape_string($connect, $lot_id);

    $query = "SELECT l.*, c.name as category,
		(SELECT MAX(w.price) FROM `wager` `w` WHERE w.lot_id = $lot_id) as max_wager
        FROM `lot` `l`
        JOIN `category` `c` ON c.id = l.category_id
        WHERE l.id = $lot_id";

    $res = mysqli_query($connect, $query);

    return mysqli_fetch_assoc($res);
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
    $user_id = mysqli_real_escape_string($connect, $user_id);
    $sql = "SELECT w.date as `date`,
        w.price as `price`,
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
        ORDER BY w.date DESC";
    $wagers = mysqli_fetch_all(mysqli_query($connect, $sql), MYSQLI_ASSOC);

    $res = [];
    foreach ($wagers as $wager) {
        $id = $wager['lot_id'];
        if (empty($res[$id]) || ($res[$id]['price'] < $wager['price'])) {
            $res[$id] = $wager;
        }
    }

    return $res;
}

/**
 * Возвращает количество найденных записей совпадающих с указанной строкой
 *
 * @param object $connect объект соединения с базой данных
 * @param string $search  строка по которой производится поиск
 *
 * @return integer mysqli_num_rows число с количеством найденных столбцов
 */
function get_search_result_count($connect, $search)
{
    $search = mysqli_real_escape_string($connect, $search);

    $res = mysqli_query($connect,
        "
        SELECT * FROM `lot` `l`
        WHERE l.date_end > NOW() AND
        MATCH(l.name, l.description) AGAINST('$search')");

    return mysqli_num_rows($res);
}

/**
 * Возвращает количество найденных записей лотов для данной категории
 *
 * @param object  $connect     объект соединения с базой данных
 * @param integer $category_id идентификатор категории
 *
 * @return integer mysqli_num_rows число с количеством найденных столбцов
 */
function get_lot_by_category_result_count($connect, $category_id)
{
    $category_id = mysqli_real_escape_string($connect, $category_id);

    $res = mysqli_query($connect, "SELECT * FROM `lot`
        WHERE date_end > NOW() AND category_id = $category_id");

    return mysqli_num_rows($res);
}

/**
 * Возвращает найденные записи совпадающие с указанной строкой
 *
 * @param object  $connect         объект соединения с базой данных
 * @param string  $search          строка по которой производится поиск
 * @param integer $max_page_result максмимальное количество результатов на странице
 * @param integer $page            число с текущей страницей
 *
 * @return array mysqli_fetch_all массив со списком результатов поиска
 */
function get_search_result($connect, $search, $max_page_result, $page)
{
    $search = mysqli_real_escape_string($connect, $search);
    $max_page_result = mysqli_real_escape_string($connect, $max_page_result);
    $page = mysqli_real_escape_string($connect, $page);

    $offset = ($page - 1) * $max_page_result;

    $res = mysqli_query($connect,
        "
        SELECT
        l.id,
        l.name as name,
        l.start_price,
        l.img,
        c.name as category,
        l.date_end,
        MATCH(l.name, l.description) AGAINST('$search') as score
        FROM `lot` `l`
        JOIN `category` `c` ON c.id = l.category_id
        WHERE
        l.date_end > NOW() AND
        MATCH(l.name, l.description) AGAINST('$search')
        ORDER BY l.date_create DESC LIMIT $offset, $max_page_result");

    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Формирует ассоциативный массив с данными текущего состояния ставки
 *
 * @param array  $wager   ассоциативный массив с данными ставки
 * @param string $user_id строка с идентификатором пользователя
 *
 * @return array ассоциативный массив с данными текущего состояния ставки
 */
function get_wager_status($wager, $user_id)
{
    $time_end = strtotime($wager['date_end']);
    $is_close_to_completion = false;
    $is_finishing = false;
    $is_win = false;

    if ((time() + 3600) > $time_end
        && time() < $time_end
    ) {
        $is_close_to_completion = true;
    } elseif (time() > $time_end) {
        $is_finishing = true;

        if ($wager['winner_id']
            === $user_id
        ) {
            $is_win = true;
        }

    }

    return [
        'is_close_to_completion' => $is_close_to_completion,
        'is_finishing' => $is_finishing,
        'is_win' => $is_win,
    ];
}

/**
 * Возвращает данные пользователя с указанным идентификатором
 *
 * @param object $connect объект соединения с базой данных
 * @param string $user_id строка с идентификатором пользователя
 *
 * @return array ассоциативный массив с данными пользователя
 */
function get_user_data($connect, $user_id)
{
    return mysqli_fetch_assoc(mysqli_query($connect,
        "SELECT * FROM `user` WHERE id = ".$user_id));
}
