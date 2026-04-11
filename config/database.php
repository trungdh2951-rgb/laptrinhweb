<?php
$host = '127.0.0.1';
$dbname = 'web_ban_hang';
$username = 'root';
$password = '';
$port = 3306;

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
?>