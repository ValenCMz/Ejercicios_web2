<?php
require_once('./Controller/encomiendasController.php');

define('BASE_URL', '//'.$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['PHP_SELF']).'/');


// lee la acción
if (!empty($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'home'; // acción por defecto si no envían
}

$encomiendasController = new encomiendasController();



$params = explode('/', $action);

var_dump($params);

// determina que camino seguir según la acción
switch ($params[0]) {
    case 'insertar':
        $encomiendasController->agregarEncomienda();
        break;
    default: 
    echo('404 Page not found'); 
    break;
}


?>