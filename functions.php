<?php

/**
 * Выводит на экран в удобном виде данные переданного ассоциативного массива
 *
 * @param array   $data      ассоциативный массив с данными
 * @param boolean $is_active флаг вывода
 */
function debug($data, $is_active = true)
{
    if ($is_active) {
        echo '<pre>'.print_r($data, true).'</pre>';
    }
}

/**
 * Возвращает строку с ценой в отформатированном виде
 *
 * @param string $price строка с необработанной ценой
 *
 * @return string number_format строка с отформатированной ценой
 */
function to_format_currency($price)
{
    return number_format($price, 0, '', ' ');
}

/**
 * Возвращает строку с оставшимся до конца торгов временем ставки в отформатированном виде
 *
 * @param string $date строка с оставшимся до конца торгов временем ставки необработанно виде
 *
 * @return array строка с отформатированным временем
 */
function get_dt_range($date)
{
    return explode(':', date('H:i', strtotime($date) - time()));
}

/**
 * Создаёт и возвращает объект соединения с базой данных
 *
 * @param array $config массив с настройками для соединения с базой данных
 *
 * @return object объект соединения с базой данных
 */
function connect_db($config)
{
    $connect = mysqli_connect($config['HOST'], $config['USER'],
        $config['PASSWORD'], $config['DB'])
    or die('Ошибка подключения '.mysqli_connect_error());
    mysqli_set_charset($connect, 'utf-8');

    return $connect;
}

/**
 * Возвращает обработанное значение из $_POST массива с указанным именем, или пустую строку
 *
 * @param string $name строка с именем нужного поля в $_POST массиве
 *
 * @return string найденное поле из $_POST массива, или пустая строка
 */
function getPostVal($name)
{
    return isset($_POST[$name]) ? htmlspecialchars($_POST[$name]) : '';
}

/**
 * Возвращает флаг указывающий попадает ли переданное значение в обозначенный числовой диапазон
 *
 * @param integer $value проверяемое число
 * @param integer $min   минимальное число доспустимого диапазона
 * @param integer $max   максимальное число доспустимого диапазона
 *
 * @return boolean результат проверки, указывающий входит ли данное число в нужный диапазон (true) или нет (false)
 */
function check_field_len($value, $min, $max)
{
    return $value >= $min && $value <= $max;
}

/**
 * Возвращает обработанное значение из $_POST массива с указанным именем, или пустую строку
 *
 * @param string $value строка с данными который нужно проверить
 * @param array  $rules ассоциативный массив со списком правил для валидации данного поля
 *
 * @return string|null $error если была найдена ошибка, то будет возвращён её текст, иначе null
 */
function validate_field($value, $rules)
{
    $error = null;

    foreach ($rules as $rule => $config) {
        if ($rule === 'length'
            && !check_field_len(mb_strlen($value), $config['min'],
                $config['max'])
        ) {
            $error
                = "Не корректная длинна строки, минимальная допустимая длина {$config['min']}, a максимальная {$config['max']}";
        }

        if ($rule === 'min' && $value < $config) {
            $error = "Недопустимое число, минимальное значение {$config}";
        }

        if ($rule === 'check_date' && strtotime($value) < $config) {
            $error
                = "Дата окончания торгов должна быть как минимум +1 день от сегодняшней даты";
        }

        if ($rule === 'match' && !preg_match($config, $value)) {
            $error = "Не верный формат строки";
        }

        if ($config === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $error = "Не корретный e-mail";
        }
    }

    return $error;
}

/**
 * Создаёт новую запись в базе данных, добавляя туда лот с указанными параметрами
 *
 * @param object $connect  объект соединения с базой данных
 * @param array  $data_lot ассоциативный массив с данными нового лота
 * @param array  $img      ассоциативный массив $_FILES с данными изображения для нового лота
 * @param string $user_id  строка с идентификатором пользователя создавшего новый лот
 */
function create_new_lot($connect, $data_lot, $img, $user_id)
{
    $img_name = $img['name'];
    $img_path = __DIR__.'/uploads/';
    $img_url = '/uploads/'.$img_name;

    $stmt = mysqli_prepare($connect, "
        INSERT INTO `lot`
        (`date_create`, `name`, `description`, `category_id`, `start_price`, `img`, `date_end`, `bid_step`, `author_id`) VALUES
        (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, 'ssssssss',
        $data_lot['lot-name'],
        $data_lot['message'],
        $data_lot['category'],
        $data_lot['lot-rate'],
        $img_url,
        $data_lot['lot-date'],
        $data_lot['lot-step'],
        $user_id
    );

    if (mysqli_stmt_execute($stmt)) {
        move_uploaded_file($img['tmp_name'], $img_path.$img_name);
        header("Location: /?page=lot&lot_id=".mysqli_insert_id($connect));
    }
}

/**
 * Возвращает флаг указывающий занят ли переданный почтовый ящик или нет
 *
 * @param object $connect объект соединения с базой данных
 * @param string $email   строка с почтовым ящиком наличие которого нужно проверить в базе данных
 *
 * @return boolean
 */
function email_exist($connect, $email)
{
    $email = mysqli_real_escape_string($connect, $email);
    $res = mysqli_query($connect,
        "SELECT * FROM `user` WHERE email = '$email'");

    return mysqli_num_rows($res) === 0;
}

/**
 * Создаёт новую запись в базе данных, добавляя туда нового пользователя с указанными данными
 *
 * @param object $connect объект соединения с базой данных
 * @param object $data    ассоциативный массив с данными нового пользователя
 */
function create_new_user($connect, $data)
{
    $stmt = mysqli_prepare($connect, "
        INSERT INTO `user`
        (`date_registration`, `email`, `name`, `password`, `contacts`) VALUES
        (NOW(), ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, 'ssss', $data['email'], $data['name'],
        password_hash($data['password'], PASSWORD_DEFAULT), $data['message']);
    mysqli_stmt_execute($stmt);
}

/**
 * Ищет и возвращает идентификатор и пароль записи соответствующей переданному почтовому ящику
 *
 * @param object $connect объект соединения с базой данных
 * @param string $email   строка с почтовым ящиком по которому нужно найти пользователя
 *
 * @return array ассоциативный массив с паролем и идентификатор найденной записи
 */
function check_user_data($connect, $email)
{
    $email = mysqli_real_escape_string($connect, $email);
    $res = mysqli_query($connect,
        "SELECT id, password FROM `user` WHERE email = '$email'");

    return mysqli_fetch_assoc($res);
}

/**
 * Сохраняет сессию для данного пользователя
 *
 * @param string $user_id строка с идентификатором пользователя
 */
function user_authorization($user_id)
{
    $_SESSION['user_id'] = $user_id;
}

/**
 * Удаляет сессию для активного пользователя
 */
function user_exit()
{
    unset($_SESSION['user_id']);
}

/**
 * Проверяет наличие сесси для пользовательского идентификатора и возвращает флаг в соответствии с результатом
 *
 * @return boolean возвращает истину (true) если пользователь авторизован, и ложь (false) если нет
 */
function is_user_authorization()
{
    return isset($_SESSION['user_id']);
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

/**
 * Создаёт новую запись ставки в базе данных с переданными данными
 *
 * @param object $connect объект соединения с базой данных
 * @param string $lot_id  строка с идентификатором лота
 * @param string $user_id строка с идентификатором пользователя
 * @param string $price   строка с ценой
 */
function create_new_wager($connect, $lot_id, $user_id, $price)
{
    $stmt = mysqli_prepare($connect,
        'INSERT INTO `wager` (`date`, `price`, `author_id`, `lot_id`) VALUES (NOW(), ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $price, $user_id, $lot_id);
    mysqli_stmt_execute($stmt);
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
    ) { // проверка, осталось ли меньше часа до конца торгов
        $is_close_to_completion = true;
    } else {
        if (time() > $time_end) { // торги закончились
            $is_finishing = true;

            if ($wager['winner_id']
                === $user_id
            ) { // является ли автор максимальной ставки текущим пользователем
                $is_win = true;
            }
        }
    }

    return [
        'is_close_to_completion' => $is_close_to_completion,
        'is_finishing' => $is_finishing,
        'is_win' => $is_win,
    ];
}

/**
 * Обновляет статусы всех лотов время торгов для которых подошло к концу
 *
 * @param object $connect объект соединения с базой данных
 */
function update_status_lots($connect)
{
    $res = mysqli_fetch_all(
        mysqli_query($connect, '
            SELECT l.id, MAX(w.price) AS price, w.author_id
            FROM `lot` `l`
            JOIN `wager` `w` ON w.lot_id = l.id
            WHERE l.winner_id IS NULL AND NOW() > l.date_end
            GROUP BY l.id'
        ),
        MYSQLI_ASSOC
    );

    if (!empty($res)) {
        foreach ($res as $lot) {
            $lot_id = $lot['id'];
            $author_id = $lot['author_id'];
            mysqli_query($connect,
                "UPDATE `lot` SET winner_id = '$author_id' WHERE id = '$lot_id'");
            // отправить письмо победителю
        }
    }
}
