<?php

// aqui o local em que ficarão os templates no caso,
// um diretório chamado templates, no mesmo nível do renderer.php
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
// eu comentei a linha sobre o cache, se qusier usar,
// basta criar o diretório cache
// recomendo que use
$twig = new \Twig\Environment($loader, [
    // 'cache' => __DIR__ . '/cache',
]);

return $twig;
