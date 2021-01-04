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

    // affichage du rendu d'un template
echo $twig->render('article-404.html.twig', [
    // transmission de données au template
    'articles' => $articles,
    ]);