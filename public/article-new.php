<?php

// activation du système d'autoloading de Composer
require __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new Twig_Loader_Filesystem(__DIR__.'/../templates');

// instanciation du moteur de template
$twig = new Twig_Environment($loader);

$formData = [
    'name' => '',
    'description'  => '',
    'price' => '',
    'quantity' => ''
];

$errors = [];
$messages = [];

if ($_POST) {
    // remplacement des valeur par défaut par celles de l'utilisateur
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

}

// validation des données envoyées par l'utilisateur

if (empty($_POST['name'])) {
    $errors['name'] = true;
    $messages['name'] = "Merci de renseigner le nom";
} elseif (strlen($_POST['name']) < 2) {
    $errors['name'] = true;
    $messages['name'] = "Le nom doit faire 2 caractères minimum";
} elseif (strlen($_POST['name']) > 100) {
    $errors['name'] = true;
    $messages['name'] = "Le nom doit faire 100 caractères maximum";
}

if (strpos($_POST['description'], '<') !== false
    || strpos($_POST['description'], '>') !== false
) {
    $errors['description'] = true;
    $messages['description'] = "Les caractères suivants sont interdits : < >";
}

if (empty($_POST['price'])) {
    $errors['price'] = true;
    $messages['price'] = "Merci de renseigner le prix";
} elseif (!is_numeric($_POST['price'])) {
    $errors['price'] = true;
    $messages['price'] = "Le prix doit être un nombre";
}

if (empty($_POST['quantity'])) {
    $errors['quantity'] = true;
    $messages['quantity'] = "Merci de renseigner la quantité";
} elseif (!is_int(0 + $_POST['quantity']) || !is_numeric($_POST['quantity'])) {
    $errors['quantity'] = true;
    $messages['quantity'] = "La quantité doit être un entier";
} elseif (($_POST['quantity']) <= 0) {
    $errors['quantity'] = true;
    $messages['quantity'] = "La quantité doit être positive";
}

// redirection vers la page article

if (!$errors) {
    $url = 'articles.php';
    header("Location: {$url}", true, 302);
    exit();
}

// affichage du rendu d'un template
echo $twig->render('article-new.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'messages' => $messages,
    'formData' => $formData,
]);