<?php
session_start();
include_once $_SERVER["DOCUMENT_ROOT"].'/ashish_rest/constant.php';
include_once LIB_PATH.DS.'an_db.php';
include_once SITE_PATH.DS.'helpers.php';
$db = new an_db(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

?>