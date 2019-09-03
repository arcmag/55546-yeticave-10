<?php

require_once 'init.php';

if(!is_user_authorization()) {
    http_response_code(403);
    header('Location: /');
    exit;
}

$connect = connect_db(DB_CONFIG);

$errors = [];
$field_rules = [
    'lot-name' => ['length' => ['min' => 10, 'max' => 255]],
    'category' => null,
    'message' => ['length' => ['min' => 10, 'max' => 2000]],
    'lot-rate' => ['min' => 1],
    'lot-step' => ['min' => 1],
    'lot-date' => ['match' => '/^\d{4}-\d{2}-\d{2}$/', 'check_date' => time() + 3600 * 24]
];

// проверка полей
foreach($field_rules as $field_name => $rules) {
    // получение текущего значения поля
    $value = $_POST[$field_name];

    // проверка на заполненность
    if(empty($value)) {
        $errors[$field_name] = 'Поле не заполнено';
        continue;
    }

    // если нет правил валидации, пропуск итерации
    if(!$rules) {
        continue;
    }

    // проход по списку установленных правил валидации
    if($error = validate_field($value, $rules)) {
        $errors[$field_name] = $error;
    }
}

// проверка файла
$file_field = 'lot-img';
$img_types = ['image/png', 'image/jpeg'];
if(isset($_FILES[$file_field]) && $_FILES[$file_field]['tmp_name'] !== '') {
    if(!in_array(mime_content_type($_FILES[$file_field]['tmp_name']), $img_types)) {
        $errors[$file_field] = 'Не корректный тип изображения, достпны: ' . implode(', ', $img_types);
    }
} else {
    $errors[$file_field] = 'Вы не выбрали изображение для лота';
}

if(count($errors) === 0) {
    create_new_lot($connect, $_POST, $_FILES[$file_field], $_SESSION['user_id']);
    exit;
}

debug($errors);

$user_name = 'Николай';

$categories = get_categories($connect);

$page_title = 'Добавление лота';
$page_template = include_template('add.php', [
    'categories' => $categories,
]);

print(include_template('layout.php', [
    'title' => $page_title,
    'is_auth' => rand(0, 1),
    'user_name' => $user_name,
    'categories' => $categories,
    'content' => $page_template,

    'connect' => $connect,
    'errors' => $errors,
]));
