<?php

// activation du système d'autoloading de Composer
require __DIR__ . '/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

// instanciation du moteur de template
$twig = new \Twig\Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
    // activation du cache, au passage en prod
    //'cache' => __DIR__ . '/../var/cache',
]);

// chargement de l'extension Twig_Extension_Debug, à enlever lors du passage en prod
$twig->addExtension(new \Twig\Extension\DebugExtension());

$articles = require __DIR__.'/articles-data.php';
require __DIR__.'/articles-lib.php';


if (!isset($_GET['id']) || empty($_GET['id'] || !articleExists($_GET['id'], $articles))) {
    $url='Location:./articles-404.php';
    header($url, true, 302);
    exit();
} else {
    $url='Location:./articles.php';
    header($url, true, 302);
    exit();
};



