<?php

namespace GuPes\Framework\Users\Controllers;

use App\Controllers\AppController;
use GuPes\Framework\Users\Models\User;

class UsersController extends  AppController
{
    public function index()
    {
        return $this->twig->render('index.html', ['users' => User::all()]);
    }

    // Novo action que cadastra usuários
    public function create()
    {
        $user = new User;
        $user->name = 'Erik';
        $user->email = base64_encode(random_bytes(10)) . '@example.com';
        $user->password = password_hash('secret', PASSWORD_DEFAULT);
        $user->save();

        return header('location: /users');
    }
}