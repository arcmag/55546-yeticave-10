<?php

error_reporting(E_ALL);

session_start();

require_once 'config/db.php';

require_once './helpers.php';
require_once './data.php';
require_once './functions.php';

$user = null;
