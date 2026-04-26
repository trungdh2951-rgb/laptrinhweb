<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

function showFlash() {
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        echo "<div class='alert {$flash['type']}'>{$flash['message']}</div>";
        unset($_SESSION['flash']);
    }
}