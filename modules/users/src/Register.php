<?php

namespace GuPes\Framework\Users;

use Twig\Environment;
use GuPes\Framework\Router;
use GuPes\Framework\Users\Models\User;
use GuPes\Framework\Users\Observers\UserObserver;

class Register
{
    public static function handle(Environment $twig, Router  $router)
    {
        //avisando ao Eloquent que o Observer User será usado
        User::observe(UserObserver::class);
        //Aqui registro a rota
        $loader = $twig->getLoader();
        $loader->addPath(__DIR__.'/../templates');

        //Aqui registro as rotas
        $router->get('/users', 'GuPes\Framework\Users\Controllers\UsersController::index');

        // nova rota
        $router->get('/users/create', 'GuPes\Framework\Users\Controllers\UsersController::create');
    }
}