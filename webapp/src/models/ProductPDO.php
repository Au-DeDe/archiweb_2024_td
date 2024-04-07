<?php

require_once MODELSDIR.'/ProductModel.php';
class ProductPDO{
    private $pdo;

    // Constructeur pour initialiser la connexion PDO
    public function __construct($host, $dbname, $username, $password) {
        // Création de la chaîne de connexion à la base de données
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        // Création d'une instance PDO pour établir la connexion
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            // Activer les exceptions pour les erreurs PDO
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // En cas d'erreur de connexion, afficher le message d'erreur
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
            die(); // Arrêter le script
        }
    }

    public function getAllProducts(): array
    {
        try {
            // Préparer et exécuter la requête SQL
            $stmt = $this->pdo->prepare("SELECT * FROM product");
            $stmt->execute();

            // Récupérer les résultats sous forme d'objets Product
            $products = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new ProductModel($row['ProductID'], $row['Name'], $row['ProductNumber'], $row['MakeFlag'],
                    $row['Color'], $row['SafetyStockLevel'], $row['ReorderPoint'],$row['DaysToManufacture'], $row['Class'],
                    $row['SellStartDate'], $row['rowguid'], $row['ModifiedDate']);
                $products[] = $product;
            }
            return $products;
        } catch(PDOException $e) {
            // En cas d'erreur de requête, afficher le message d'erreur
            echo "Erreur de requête : " . $e->getMessage();
            return []; // Retourner un tableau vide en cas d'erreur
        }
    }
}