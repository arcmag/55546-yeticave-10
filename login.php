<?php

require_once 'init.php';

if (is_user_authorization()) {
    header('Location: /');
    exit;
}

$connect = connect_db(DB_CONFIG);

$errors = [];
$field_rules = ['email' => null, 'password' => null];

if (!empty($_POST)) {
    foreach ($field_rules as $field_name => $rules) {
        if (empty($_POST[$field_name])) {
            $errors[$field_name] = 'Поле не заполнено';
            continue;
        }
    }


    if (empty($errors['password']) && empty($errors['email'])) {
        $data_user = check_user_data($connect, $_POST['email']);

        if (!$data_user) {
            $errors['email'] = 'Пользователя с таким e-mail не существует';
        } else {
            if (!password_verify($_POST['password'], $data_user['password'])) {
                $errors['password'] = 'Введён не верный пароль';
            } else {
                user_authorization($data_user['id']);
                header('Location: /');
                exit;
            }
        }
    }
}

$categories = get_categories($connect);

$page_title = 'Авторизация';
$page_template = include_template('login.php', [
    'errors' => $errors,
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
]));
