<?php
error_reporting(E_ALL);
ini_set('display_errors', true);


require __DIR__.'/vendor/autoload.php';

// defino o método http e a url amigável
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'] ?? '/';

// instancio o Router
$router = new \GuPes\Framework\Router($method, $path);

// registro as rotas
$router->get('/', function () {
    return 'Olá mundo';
});

$router->get('/ola-{nome}', function ($params) {
    return 'Olá ' . $params[1]; // o parametro 0 é a rota toda
});

// faço o router encontrar a rota que o usuário acessou
$result = $router->handler();

// se retornar false, dou um erro 404 de página não encontrada
if (!$result) {
    http_response_code(404);
    echo 'Página não encontrada!';
    die();
}

// imprimo a página atual
echo $result($router->getParams());
