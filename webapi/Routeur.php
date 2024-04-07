<?php
class Routeur{
    function manageRequest(){
        $url = $_GET['url'] ?? '/';
        $urlParts = explode('/', $url);
        $method = $_SERVER['REQUEST_METHOD'];
    
        switch($method) {
            case 'GET':
                if(isset($urlParts[0]) && $urlParts[0] === 'produit' && isset($urlParts[1])) {
                    $this->callControllerMethod('PDOController', 'getProductById', $urlParts[1]);

                } 
                elseif(isset($urlParts[0]) && $urlParts[0] === 'produit') {
                    $this->callControllerMethod('PDOController', 'getAllProducts');
                } 
                else {
                    $this->callControllerMethod('ErrorController', 'badRequest'); // 400
                }
                break;

            case 'PUT':
                if(isset($urlParts[0]) && $urlParts[0] === 'produit' && isset($urlParts[1])) {
                    $this->callControllerMethod('PDOController', 'updateProduct', $urlParts[1]);
                } else {
                    $this->callControllerMethod('ErrorController', 'badRequest'); // 400
                }
                break;

            case 'PATCH':
                if(isset($urlParts[0]) && $urlParts[0] === 'produit' && isset($urlParts[1])) {
                    $this->callControllerMethod('PDOController', 'patchProduct', $urlParts[1]);
                } else {
                    $this->callControllerMethod('ErrorController', 'badRequest'); // 400
                }
                break;

            case 'POST':
                if(isset($urlParts[0]) && $urlParts[0] === 'produit') {
                    $this->callControllerMethod('PDOController', 'addProduct');
                } else {
                    $this->callControllerMethod('ErrorController', 'badRequest'); // 400
                }
                break;

            case 'DELETE':
                if(isset($urlParts[0]) && $urlParts[0] === 'produit' && isset($urlParts[1])) {
                    $this->callControllerMethod('PDOController', 'deleteProduct', $urlParts[1]);
                } else {
                    $this->callControllerMethod('ErrorController', 'badRequest'); // 400
                }
                break;

            default:
                $this->callControllerMethod('ErrorController', 'badMethod'); // 405
                break;
        }
    }
    function callControllerMethod($controllerName, $methodName, $id = null) {
        require_once CONTROLLERSDIR.DS.$controllerName.'.php';
        $controller = new $controllerName();
        if($id !== null) {
            $controller->$methodName($id);
        } else {
            $controller->$methodName();
        }
    }
}
