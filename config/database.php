<?php
$host = 'localhost';
$dbname = 'web_ban_hang';
$username = 'root';
$password = '123456';
$port = 3307;

$conn = new mysqli($host, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die('Kết nối thất bại: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

echo "Kết nối thành công!";
?>