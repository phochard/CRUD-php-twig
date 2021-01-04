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

$formData = [
    'name'=>'',
    'description'=>'',
    'price'=>'',
    'quantity'=>'',
];
//tableau contenant la liste des erreurs
$errors =[];
//tableau contenant les messages d'erreurs
$messages = [];

$updated_list= [];
//vérification de présence de données

if ($_POST){
//dump($_POST);
    
    //remplacement des valeurs par défaut
    if (isset($_POST['name'])) {
        $formData['name'] = $_POST['name'];
    }
    if (isset($_POST['description'])) {
        $formData['description'] = $_POST['description'];
    }
    if (isset($_POST['price'])) {
        $formData['price'] = $_POST['price'];
    }
    if (isset($_POST['quantity'])) {
        $formData['quantity'] = $_POST['quantity'];
    }

    //validation des données 
    if (!isset($_POST['name']) || empty($_POST['name'])){
        $errors['name'] = true;
        $messages['name'] = 'You have to choose a name for your article.';
    } elseif (strlen($_POST['name']) < 2 || strlen($_POST['name']) > 100) {
        $errors['name'] = true;
        $messages['name'] = 'You have to choose a name between 2 and 100 caracters.';
    };

    if (!isset($_POST['description'])) {
        $errors['description'] = true;
        $messages['description'] = 'No corresponding field.';
    } elseif ( strpos($_POST['description'], '<') || strpos($_POST['description'], '>')) {
        $errors['description'] = true;
        $messages['description'] = 'Symbols < and > are not allowed.';
    };

    if (!isset($_POST['price']) || empty($_POST['price'])){
        $errors['price'] = true;
        $messages['price'] = 'Price is required.';
    } elseif (!is_numeric($_POST['price'])) {
        $errors['price'] = true;
        $messages['price'] = 'The price must be a number.';
    };

    if (!isset($_POST['quantity']) || empty($_POST['quantity'])){
        $errors['quantity'] = true;
        $messages['quantity'] = 'Quantity is required.';
    } elseif (filter_var($_POST['quantity'], FILTER_VALIDATE_INT) === false ) {
        $errors['quantity'] = true;
        $messages['quantity'] = 'The quantity must be an integer.';
    } elseif ($_POST['quantity'] < 0) {
        $errors['quantity'] = true;
        $messages['quantity'] = 'The quantity must be higher than 0.';
    };
    
    if(!$errors){
        $last_key = key(array_slice($articles, -1, 1, true));
        $new_article = [
                        'id' => $articles[$last_key]['id']+1,
                        'name' => $_POST['name'],
                        'description' => $_POST['description'],
                        'price' => $_POST['price'],
                        'quantity' => $_POST['quantity'],
        ];
        $updated_list = array_push($articles,$new_article);
        dump($new_article);
        dump($updated_list);
        
        /*  $url='Location:./articles.php';
        header($url, true, 302);
        exit(); */
    }

}

// affichage du rendu d'un template
echo $twig->render('article-new.html.twig', [
    'formData'=> $formData,
    'errors' => $errors,
    'messages' => $messages,
    'updated_list' => $updated_list,
]);