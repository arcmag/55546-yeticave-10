<?php

error_reporting(E_ALL);

session_start();

$timezone = 'Asia/Novosibirsk';

date_default_timezone_set($timezone);

require_once 'vendor/autoload.php';

require_once 'config/db.php';
require_once 'config/mail_winner.php';

require_once 'helpers.php';
require_once 'data.php';
require_once 'functions.php';

$connect = connect_db(DB_CONFIG);
$user = !empty($_SESSION['user_id']) ? get_user_data($connect,
    $_SESSION['user_id']) : null;

update_status_lots($connect);
