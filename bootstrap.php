<?php
error_reporting(E_ALL);
ini_set('display_errors', true);


require __DIR__.'/vendor/autoload.php';
require __DIR__.'/database.php';
//Carregando o arquivo de view armazenando o valor do return em uma variável
$twig = require(__DIR__ . '/renderer.php');

// defino o método http e a url amigável
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'] ?? '/';

// instancio o Router
$router = new \GuPes\Framework\Router($method, $path);

$router->get('/', function (){
    return 'Olá Mundo';
});

GuPes\Framework\Users\Register::handle($twig, $router);

// faço o router encontrar a rota que o usuário acessou
$result = $router->handler();

// se retornar false, dou um erro 404 de página não encontrada
if (!$result) {
    http_response_code(404);
    echo 'Página não encontrada!';
    die();
}

//pego os dados da entidade
$data = $result->getData();

//rodo os middlewares before
foreach ($data['before'] as $before){
    //rodo o middleware
    if(!before($router->getParams())){
        //se retornar false eu paro a execução
        die();
    }
}

//rodo a ação principal
// verifico se é uma função anônima
if ($data['action'] instanceof Closure) {
    // imprimo a página atual
    echo $data['action']($router->getParams());

// se não for uma função anônima e for uma string
} elseif (is_string($data['action'])) {
    // eu quebro a string nos dois-pontos, dois::pontos
    // transformando em array
    $data['action'] = explode('::', $data['action']);

    // instancio o controller
    $controller = new $data['action'][0]($twig);
    // guardo o método a ser executado (em um controller ele se chama action)
    $action = $data['action'][1];

    // finalmente executo o método da classe
    echo $controller->$action($router->getParams());
}

//rodo os middlewares after
foreach($data['after'] as $after){
    //rodo o middleware
    if(!$after($router->getParams())){
        //se retornar false eu paro a execução
        die();
    }
}
