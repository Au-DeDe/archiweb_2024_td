<?php
class Routeur{
    function manageRequest(){
        $url = $_GET['url'] ?? '/';
        $urlParts = explode('/', $url);

        switch($urlParts[0]) {
            case '':
                $this->callControllerMethod('homeController', 'index');
                break;
            case 'produit':
                if(isset($urlParts[1])){
                    $this->callControllerMethod('ProductController', 'listone', $urlParts[1]);
                } else {
                    $this->callControllerMethod('ProductController', 'listall');
                }
                break;
            default:
                // En cas de doute, on redirige vers l'accueil. 
                $this->callControllerMethod('homeController', 'index');
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
