<?php

require_once 'init.php';

if (!is_user_authorization()) {
    http_response_code(403);
    header('Location: /login.php');
    exit;
}

$errors = [];
$field_rules = [
    'lot-name' => ['length' => ['min' => 3, 'max' => 255]],
    'category' => null,
    'message' => ['length' => ['min' => 10, 'max' => 2000]],
    'lot-rate' => ['min' => 1],
    'lot-step' => ['min' => 1],
    'lot-date' => [
        'match' => '/^\d{4}-\d{2}-\d{2}$/',
        'check_date' => true,
    ],
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

    $file_field = 'lot-img';
    $img_types = ['image/png', 'image/jpeg'];
    if (isset($_FILES[$file_field])
        && $_FILES[$file_field]['tmp_name'] !== ''
    ) {
        if (!in_array(mime_content_type($_FILES[$file_field]['tmp_name']),
            $img_types)
        ) {
            $errors[$file_field] = 'Не корректный тип изображения, достпны: '
                .implode(', ', $img_types);
        }
    } else {
        $errors[$file_field] = 'Вы не выбрали изображение для лота';
    }

    if (count($errors) === 0) {
        $res = create_new_lot($connect, $_POST, $_FILES[$file_field],
            $_SESSION['user_id']);

        if ($res) {
            header("Location: /lot.php?id=".mysqli_insert_id($connect));
            exit;
        }
    }
}

$categories = get_categories($connect);

$page_title = 'Добавление лота';
$page_template = include_template('add.php', [
    'categories' => $categories,
    'errors' => $errors,
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'user' => $user,
    'categories' => $categories,
    'content' => $page_template,
    'connect' => $connect,
]));
