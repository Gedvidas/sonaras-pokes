<?php

// Includes
require $_SERVER['DOCUMENT_ROOT'] . '/routes/web.php';
require $_SERVER['DOCUMENT_ROOT'] . '/routes/api.php';

// Mysql config
const DB_HOST = "localhost";
const DB_NAME = "sonaras";
const DB_USER = "root";
const DB_PASS = "";
const DB_CHARSET = "utf8";
// --------------------

const URL_ROOT = DB_HOST . '/pokes';

define("VIEW_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/resources/view/');
define("JS_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/resources/js/');
define("CSS_ROOT", $_SERVER['DOCUMENT_ROOT'] . '/public/css/');
define("CSV_FILE", $_SERVER['DOCUMENT_ROOT'] . '/data.csv');
