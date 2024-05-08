<?php

require_once 'controller.php';
require_once 'model/model.php';
require_once 'view/view.php';

$controller = new Controller(new Model(), new View());

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        $controller->processGetRequest();
        break;

    case 'POST':
        $controller->processPostRequest();
        break;

    case 'DELETE':
        $controller->processDeleteRequest();
        break;

}