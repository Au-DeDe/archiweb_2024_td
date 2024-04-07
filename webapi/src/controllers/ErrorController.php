<?php

final class ErrorController {
    function badRequest() {
        // En-tête HTTP 400 : Requête incorrecte
        http_response_code(400);

        $error_message = array(
            "error" => array(
                "code" => 400,
                "message" => "Requete incorrecte"
            )
        );

        $json_response = json_encode($error_message);

        header('Content-Type: application/json');
        echo $json_response;
    }
    function forbidden() {
        // En-tête HTTP 403 : Interdit d'accès. 
        http_response_code(403);

        $error_message = array(
            "error" => array(
                "code" => 403,
                "message" => "Interdit d'acces"
            )
        );

        $json_response = json_encode($error_message);
        echo $json_response;
    }
    function notFound() {
        // En-tête HTTP 404 : Page non trouvée
        http_response_code(404);

        $error_message = array(
            "error" => array(
                "code" => 404,
                "message" => "Page non trouvee"
            )
        );

        $json_response = json_encode($error_message);

        header('Content-Type: application/json'); 
        echo $json_response;
    }
    function badMethod(){
        // En-tête HTTP 405 : Méthode non autorisée
        http_response_code(405);

        $error_message = array(
            "error" => array(
                "code" => 405,
                "message" => "Méthode non autorisee"
            )
        );

        $json_response = json_encode($error_message);

        header('Content-Type: application/json');
        echo $json_response;
    }
}