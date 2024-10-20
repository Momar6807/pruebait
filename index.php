<?php




$url = $_SERVER['REQUEST_URI'];

switch ($url) {
    case '/':
        require './views/index.php';
        break;
    default:
        require './views/404.php';
        break;
}