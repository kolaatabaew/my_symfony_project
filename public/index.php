<?php

require_once dirname(__DIR__) . '/src/Controller/MovieController.php';
require_once dirname(__DIR__) . '/src/Controller/BookController.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

use App\Controller\MovieController;
use App\Controller\BookController;

$movieController = new MovieController();
$bookController = new BookController();

if ($path === '/movies' || $path === '/') {
    echo $movieController->index();
} elseif ($path === '/movies/random') {
    echo $movieController->random();
} elseif (preg_match('#^/movies/(\d+)$#', $path, $matches)) {
    echo $movieController->show((int)$matches[1]);
} elseif ($path === '/books') {
    echo $bookController->index();
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
}
