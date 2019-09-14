<?php

require_once 'init.php';

if (is_user_authorization()) {
    http_response_code(403);
    header('Location: /');
    exit;
}

$connect = connect_db(DB_CONFIG);

$errors = [];
$field_rules = [
    'email' => ['email'],
    'password' => ['length' => ['min' => 6, 'max' => 64]],
    'name' => ['length' => ['min' => 3, 'max' => 50]],
    'message' => ['length' => ['min' => 10, 'max' => 2000]],
];

if (!empty($_POST)) {
    foreach ($field_rules as $field_name => $rules) {
        $value = trim($_POST[$field_name]);

        if (empty($value)) {
            $errors[$field_name] = 'Поле не заполнено';
            continue;
        }

        if (!$rules) {
            continue;
        }

        if ($error = validate_field($value, $rules)) {
            $errors[$field_name] = $error;
        }
    }

    $field_name = 'email';
    if (empty($errors[$field_name])
        && !email_exist($connect, $_POST[$field_name])
    ) {
        $errors[$field_name] = 'Данный E-mail уже используется';
    }

    if (count($errors) === 0) {
        create_new_user($connect, $_POST);
        header("Location: /");
        exit;
    }
}

$categories = get_categories($connect);

$page_title = 'Регистрация';
$page_template = include_template('sign-up.php', [
    'errors' => $errors,
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'categories' => $categories,
    'content' => $page_template,
]));
