<?php

function debug($data, $is_active = true) {
    if($is_active) {
        echo '<pre>' . print_r($data, true) . '</pre>';
    }
}

function to_format_currency($price) {
    return number_format($price, 0, '', ' ');
}

function get_dt_range($date) {
    return explode(':', date('H:i', strtotime($date) - time()));
}

function connect_db($config) {
    $connect = mysqli_connect($config['HOST'], $config['USER'], $config['PASSWORD'], $config['DB'])
        or die('Ошибка подключения ' . mysqli_connect_error());
    mysqli_set_charset($connect, 'utf-8');

    return $connect;
}

function getPostVal($name) {
    return htmlspecialchars($_POST[$name]) ?? '';
}

function check_field_len($value, $min, $max) {
    return $value >= $min && $value <= $max;
}

function validate_field($value, $rules) {
    $error = null;

    foreach($rules as $rule => $config) {
        if($rule === 'length' && !check_field_len(mb_strlen($value), $config['min'], $config['max'])) {
            $error = "Не корректная длинна строки, минимальная допустимая длина {$config['min']}, a максимальная {$config['max']}";
        }

        if($rule === 'min' && $value < $config) {
            $error = "Недопустимое число, минимальное значение {$config}";
        }

        if($rule === 'check_date' && strtotime($value) < $config) {
            $error = "Дата окончания торгов должна быть как минимум +1 день от сегодняшней даты";
        }

        if($rule === 'match' && !preg_match($config, $value)) {
            $error = "Не верный формат строки";
        }

        if($config === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $error = "Не корретный e-mail";
        }
    }

    return $error;
}

function create_new_lot($connect, $data_lot, $img) {
    $author_id = 1; // временный id автора лота

    $img_name = $img['name'];
    $img_path = __DIR__ . '/uploads/';
    $img_url = '/uploads/' . $img_name;

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
        $author_id
    );

    $result = mysqli_stmt_execute($stmt);

    if($result) {
        move_uploaded_file($img['tmp_name'], $img_path . $img_name);

        header("Location: /?page=lot&lot_id=" . mysqli_insert_id($connect));
    }

}

function email_exist($connect, $email) {
    $email = mysqli_real_escape_string($connect, $email);
    $res = mysqli_query($connect, "SELECT * FROM `user` WHERE email = '$email'");
    return mysqli_num_rows($res) === 0;
}

function create_new_user($connect, $data) {
    $stmt = mysqli_prepare($connect, "
        INSERT INTO `user`
        (`date_registration`, `email`, `name`, `password`, `contacts`) VALUES
        (NOW(), ?, ?, ?, ?)");

    mysqli_stmt_bind_param($stmt, 'ssss', $data['email'], $data['name'], $data['password'], $data['message']);
    mysqli_stmt_execute($stmt);

    header("Location: /");
}
