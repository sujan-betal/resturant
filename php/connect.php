<?php
$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "root";           // your MySQL password here
$db_name = "restaurant_db";
$db_port = 3306;

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

if (!$con) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

mysqli_set_charset($con, "utf8");
?>
