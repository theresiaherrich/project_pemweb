<?php
$page = $_GET['page'] ?? 'login'; // default login

$controllerName = ucfirst($page) . "Controller.php";
$controllerPath = __DIR__ . "/Controllers/" . $controllerName;

if (file_exists($controllerPath)) {
    include $controllerPath;
} else {
    echo "404 - Halaman tidak ditemukan";
}
