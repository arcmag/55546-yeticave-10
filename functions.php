<?php

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
 * @return string строка с отформатированным временем
 */
function get_date_range($date)
{
    $timestamp = strtotime($date) - time();

    $min = (int)($timestamp / 60) % 60;
    if ($min < 10) {
        $min = "0".$min;
    }
    $hour = (int)($timestamp / (60 * 60));
    if ($hour < 10) {
        $hour = "0".$hour;
    }

    return $hour.":".$min;
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
    mysqli_set_charset($connect, 'utf8');

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
                = "Не корректная длинна строки, минимальная допустимая длина 
                    {$config['min']}, a максимальная {$config['max']}";
        }

        if ($rule === 'min' && $value < $config) {
            $error = "Недопустимое число, минимальное значение {$config}";
        }

        if ($rule === 'check_date'
            && (date('d', time()) >= explode('-', $value)[2])
        ) {
            $error
                = "Дата окончания торгов должна быть как минимум +1 день от сегодняшней даты";
        }

        if ($rule === 'match' && !preg_match($config, $value)) {
            $error = "Не верный формат строки";
        }

        if ($config === 'email'
            && !filter_var($value, FILTER_VALIDATE_EMAIL)
        ) {
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
 *
 * @return boolean результат операции, в случае успеха вернёт true, иначе false
 */
function create_new_lot($connect, $data_lot, $img, $user_id)
{
    $img_name = $img['name'];
    $img_path = __DIR__.'/uploads/';
    $img_url = '/uploads/'.$img_name;

    $stmt = mysqli_prepare($connect, "
        INSERT INTO `lot`
        (`date_create`, `name`, `description`, `category_id`, `start_price`,
        `img`, `date_end`, `bid_step`, `author_id`) VALUES
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
        if (!file_exists('./uploads')) {
            mkdir('./uploads');
        }

        move_uploaded_file($img['tmp_name'], $img_path.$img_name);

        return true;
    }

    return false;
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
 * Создаёт запись в базе данных, добавляя туда нового пользователя с указанными данными
 *
 * @param object $connect объект соединения с базой данных
 * @param object $data    ассоциативный массив с данными нового пользователя
 */
function create_new_user($connect, $data)
{
    $stmt = mysqli_prepare($connect,
        "INSERT INTO `user`
        (`date_registration`, `email`, `name`, `password`, `contacts`) VALUES
        (NOW(), ?, ?, ?, ?)");

    $pass = password_hash($data['password'], PASSWORD_DEFAULT);

    mysqli_stmt_bind_param(
        $stmt, 'ssss',
        $data['email'],
        $data['name'],
        $pass,
        $data['message']);

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
        'INSERT INTO `wager` (`date`, `price`, `author_id`, `lot_id`)
                VALUES (NOW(), ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'sss', $price,
        $user_id, $lot_id);
    mysqli_stmt_execute($stmt);
}

/**
 * Отправляет письмо победителю в торгах за лот
 *
 * @param string  $user_email строка с почтовым ящиком победившего пользователя куда будет отправлено письмо
 * @param string  $user_name  строка с именем победившего пользователя
 * @param integer $lot_id     строка с идентификатором выигранного лота
 * @param string  $lot_name   строка с именем выигранного лота
 */
function send_mail_for_winner($user_email, $user_name, $lot_id, $lot_name)
{
    $msg_content = include_template('email.php', [
        'user_name' => $user_name,
        'lot_id' => $lot_id,
        'lot_name' => $lot_name,
    ]);

    $transport = (new Swift_SmtpTransport(MAIL_WINNER_CONFIG['HOST'],
        MAIL_WINNER_CONFIG['PORT']))
        ->setUsername(MAIL_WINNER_CONFIG['USER'])
        ->setPassword(MAIL_WINNER_CONFIG['PASSWORD']);

    $message = (new Swift_Message('Ваша ставка победила'))
        ->setTo([$user_email => $user_name])
        ->setBody($msg_content, 'text/html')
        ->setFrom([MAIL_WINNER_CONFIG['FROM'] => 'YetiCave']);

    (new Swift_Mailer($transport))->send($message);
}

/**
 * Обновляет статусы всех лотов время торгов для которых подошло к концу
 *
 * @param object $connect объект соединения с базой данных
 */
function update_status_lots($connect)
{
    $lots = mysqli_fetch_all(
        mysqli_query($connect, '
            SELECT * FROM `lot`
            WHERE winner_id IS NULL AND NOW() > date_end'
        ), MYSQLI_ASSOC);
    if (empty($lots)) {
        return;
    }

    for ($i = 0; $i < count($lots); $i++) {
        $wagers = mysqli_fetch_all(
            mysqli_query($connect, '
            SELECT * FROM `wager`
            WHERE lot_id = '.$lots[$i]['id']), MYSQLI_ASSOC);

        $lots[$i]['max_wager'] = null;
        if (empty($wagers)) {
            continue;
        }

        $lots[$i]['max_wager'] = array_reduce($wagers, function ($a, $b) {
            return $a['price'] > $b['price'] ? $a : $b;
        });
    }

    foreach ($lots as $lot) {
        $wager = $lot['max_wager'];
        if (empty($wager)) {
            continue;
        }

        $lot_id = $lot['id'];
        $author_id = $wager['author_id'];
        $user = get_user_data($connect, $author_id);
        mysqli_query($connect,
            "UPDATE `lot` SET winner_id = '$author_id'
                WHERE id = '$lot_id'");

        send_mail_for_winner($user['email'], $user['name'],
            $lot_id, $lot['name']);
    }
}

/**
 * Возвращает прошедшее время с момента добавления ставки в отформатированном виде
 *
 * @param string $publication_date строка с датой добавления ставки
 *
 * @return string время в отформатированном виде
 */
function format_date_personal_lot($publication_date)
{
    $time_list = [
        'sec' => 60,
        'min' => 60 * 60,
        'hour' => (60 * 60) * 24,
        'day' => ((60 * 60) * 24) * 30,
        'month' => (((60 * 60) * 24) * 30) * 12,
    ];

    $diff_timestamp = $publication_date;
    $str_res = '';
    $time = 0;

    if ($diff_timestamp < $time_list['sec']) {
        $time = (int)$diff_timestamp;
        $str_res .= $time.' '.get_noun_plural_form($time, 'секунда',
                'секунды', 'секунд');
    } elseif ($diff_timestamp < $time_list['min']) {
        $time = (int)($diff_timestamp / $time_list['sec']);
        $str_res .= $time.' '.get_noun_plural_form($time, 'минута',
                'минуты', 'минут');
    } elseif ($diff_timestamp < $time_list['hour']) {
        $time = (int)($diff_timestamp / $time_list['min']);
        $str_res .= $time.' '.get_noun_plural_form($time, 'час',
                'часа', 'часов');
    } elseif ($diff_timestamp < $time_list['day']) {
        $time = (int)($diff_timestamp / $time_list['hour']);
        $str_res .= $time.' '.get_noun_plural_form($time, 'день',
                'дня', 'дней');
    } elseif ($diff_timestamp < $time_list['month']) {
        $time = (int)($diff_timestamp / $time_list['day']);
        $str_res .= $time.' '.get_noun_plural_form($time,
                'месяц', 'месяца', 'месяцев');
    } else {
        $time = (int)($diff_timestamp / $time_list['month']);
        $str_res .= $time.' '.get_noun_plural_form($time, 'год',
                'года', 'лет');
    }

    return $str_res.' назад';
}
