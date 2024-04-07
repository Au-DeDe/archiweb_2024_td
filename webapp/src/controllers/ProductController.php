<?php
final class ProductController{
    public function listall() {
        // Définir l'URL de l'API
        $apiUrl = WEBROOT . '/webapi/produit';
    
        // Effectuer une requête HTTP GET à l'URL de l'API
        $response = file_get_contents($apiUrl);
    
        // Vérifier si la réponse est réussie
        if ($response === false) {
            // Gérer les erreurs de requête
            http_response_code(500);
            echo "Erreur lors de la récupération des produits depuis l'API.";
            exit;
        }
    
        // Convertir la réponse JSON en tableau associatif de produits
        $products = json_decode($response, true);
    
        // Vérifier si le décodage JSON a réussi
        if ($products === null) {
            // Gérer les erreurs de décodage JSON
            http_response_code(500);
            echo "Erreur lors du traitement des données JSON.";
            exit;
        }
    
        // Afficher les produits
        require VIEWSDIR . DS . 'productView.php';
        $productView = new ProductView();
        echo $productView->listall($products);
    
        // Envoyer le code de réponse HTTP 200 (OK)
        http_response_code(200);
        exit;
    }
    

    public function listone($id){

        // Définir l'URL de l'API
        $apiUrl = WEBROOT . '/webapi/produit/' . $id;
    
        // Effectuer une requête HTTP GET à l'URL de l'API
        $response = file_get_contents($apiUrl);
    
        // Vérifier si la réponse est réussie
        if ($response === false) {
            // Gérer les erreurs de requête
            http_response_code(500);
            echo "Erreur lors de la récupération des produits depuis l'API.";
            exit;
        }
    
        // Convertir la réponse JSON en tableau associatif de produits
        $products = json_decode($response, true);
    
        // Vérifier si le décodage JSON a réussi
        if ($products === null) {
            // Gérer les erreurs de décodage JSON
            http_response_code(500);
            echo "Erreur lors du traitement des données JSON.";
            exit;
        }

        require VIEWSDIR.DS.'productView.php';
        $productView = new ProductView();
        echo $productView->listone($product);

        http_response_code(200);
        exit(); 
        
    }
}