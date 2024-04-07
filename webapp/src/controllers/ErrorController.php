<?php

final class ErrorController {
    function notFound() {
        // En-tête HTTP 404 : Page non trouvée
        http_response_code(404);

        // Affichage du message d'erreur
        echo "<h1>404 - Page non trouvée</h1>";
        echo "<p>Désolé, la page que vous demandez est introuvable.</p>";
    }

    function badRequest() {
        // En-tête HTTP 400 : Requête incorrecte
        http_response_code(400);

        // Affichage du message d'erreur
        echo "<h1>400 - Requête incorrecte</h1>";
        echo "<p>Désolé, la requête que vous avez envoyée est incorrecte.</p>";
    }

    function badMethod(){
        // En-tête HTTP 405 : Méthode non autorisée
        http_response_code(405);

        // Affichage du message d'erreur
        echo "<h1>405 - Méthode non autorisée</h1>";
        echo "<p>Désolé, la méthode que vous avez utilisée n'est pas autorisée.</p>";
    }

    function forbidden() {
        // En-tête HTTP 403 : Interdit d'accès. 
        http_response_code(403);

        echo "<h1>403 - Accès interdit</h1>";
        echo "<p>Désolé, vous n'êtes pas autorisé à accéder à cette ressource.</p>";
    }

}