<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function formatPrice($price) {
    return number_format((float)$price, 0, ',', '.') . ' đ';
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function redirect($path) {
    header('Location: ' . $path);
    exit();
}

function sanitize($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function imageUrl($filename) {
    $filename = trim((string)$filename);
    if ($filename === '') {
        return '/Web/image/logo.jpg';
    }

    $fullPath = __DIR__ . '/../image/' . $filename;
    $ver = file_exists($fullPath) ? filemtime($fullPath) : time();
    return '/Web/image/' . rawurlencode($filename) . '?v=' . $ver;
}
?>
