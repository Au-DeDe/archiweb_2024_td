<?php

require_once MODELSDIR.'/ProductModel.php';
class ProductPDO{
    private $pdo;

    // Constructeur pour initialiser la connexion PDO
    public function __construct($host, $dbname, $username, $password) {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        // Création d'une instance PDO pour établir la connexion
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
            die(); 
        }
    }

    public function getAllProducts(): array{
        try {
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
            echo "Erreur de requête : " . $e->getMessage();
            return []; 
        }
    }

    public function getProductById($id){
        if(!is_numeric($id)){ // On empêche d'appeler la base de données si l'ID n'est pas numérique. 
            header('Location:'.WEBROOT.'/400');
        }
        
        try{
            $stmt = $this->pdo->prepare("SELECT * FROM product WHERE ProductID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $product = new ProductModel($row['ProductID'], $row['Name'], $row['ProductNumber'], $row['MakeFlag'],
                $row['Color'], $row['SafetyStockLevel'], $row['ReorderPoint'],$row['DaysToManufacture'], $row['Class'],
                $row['SellStartDate'], $row['rowguid'], $row['ModifiedDate']);

            return $product;
        }
        catch(PDOException $e){
            echo "Erreur de requête : " . $e->getMessage();
            return null;
        }
    }

    public function updateProduct($id){ // PUT 
        if(!is_numeric($id)){ 
            header('Location:'.WEBROOT.'/400');
        }

        // Il n'existe pas de $_PUT en PHP, on doit donc récupérer les données autrement (JSON). 
        $putData = file_get_contents("php://input");

        // Décoder les données au format JSON
        $decodedData = json_decode($putData, true);

        // Vérifier si la décodage JSON a réussi
        if ($decodedData === null) {
            // Gérer l'erreur si le décodage JSON a échoué
            echo "Erreur lors du décodage des données JSON.";
            die(); 
        } else {
            $updateData = [
                'Name' => $decodedData['name'],
                'ProductNumber' => $decodedData['productNumber'],
                'MakeFlag' => $decodedData['makeFlag'],
                'Color' => $decodedData['color'],
                'SafetyStockLevel' => $decodedData['safetyStockLevel'],
                'ReorderPoint' => $decodedData['reorderPoint'],
                'DaysToManufacture' => $decodedData['daysToManufacture'],
                'Class' => $decodedData['class'],
                'SellStartDate' => $decodedData['sellStartDate'],
                'rowguid' => $decodedData['rowguid'],
                'ModifiedDate' => $decodedData['modifiedDate']
            ];
        }
        try{

            $this->pdo->beginTransaction(); 

            $stmt = $this->pdo->prepare("UPDATE product SET Name = :name, ProductNumber = :productNumber, MakeFlag = :makeFlag, 
            Color = :color, SafetyStockLevel = :safetyStockLevel, ReorderPoint = :reorderPoint, DaysToManufacture = :daysToManufacture, 
            Class = :class, SellStartDate = :sellStartDate, rowguid = :rowguid, ModifiedDate = :modifiedDate WHERE ProductID = :id");

            $updateData['id'] = $id;
            $stmt->execute($updateData);
            $this->pdo->commit();

            header('Location:'.WEBROOT.'/');
        }
        catch(PDOException $e){ 
            $this->pdo->rollBack();
            echo "Erreur de requête : " . $e->getMessage();
        }
    }

    public function patchProduct($id){ // PATCH

        if(!is_numeric($id)){ 
            header('Location:'.WEBROOT.'/400');
        }
        // Il n'existe pas de $_PATCH en PHP, on doit donc récupérer les données autrement (JSON). 
        $patchData = file_get_contents("php://input");
        $decodedData = json_decode($patchData, true);

        if($decodedData === null){
            echo "Erreur lors du décodage des données JSON.";
            die();
        }
        else{
            $updateData = [];
            $setClause = "";
            foreach($decodedData as $key => $value){
                // On récupère chaque clé et sa valeur pour les ajouter à la requête SQL
                // SetClause contient les noms des colonnes à mettre à jour (name, productNumber, etc.)
                $setClause .= $key . " = :" . $key . ", ";
                $updateData[$key] = $value;
            }
        }

        $setClause = rtrim($setClause, ", "); // On enlève la dernière virgule

        try{
            $this->pdo->beginTransaction(); 

            $stmt = $this->pdo->prepare("UPDATE product SET $setClause WHERE ProductID = :id");

            $updateData['id'] = $id;
            $stmt->execute($updateData);
            $this->pdo->commit();

            header('Location:'.WEBROOT.'/');
        }
        catch(PDOException $e){ 
            $this->pdo->rollBack();
            echo "Erreur de requête : " . $e->getMessage();
        }
    }

    public function addProduct(){
        // On reçoit directement les données via le formulaire, on n'a pas besoin de les décoder ! 
        if(isset($_POST['name']) && isset($_POST['productNumber']) && isset($_POST['makeFlag']) && isset($_POST['color']) && isset($_POST['safetyStockLevel']) && isset($_POST['reorderPoint']) && isset($_POST['daysToManufacture']) && isset($_POST['class']) && isset($_POST['sellStartDate']) && isset($_POST['rowguid']) && isset($_POST['modifiedDate'])){
            $addData = [
                'Name' => $_POST['name'],
                'ProductNumber' => $_POST['productNumber'],
                'MakeFlag' => $_POST['makeFlag'],
                'Color' => $_POST['color'],
                'SafetyStockLevel' => $_POST['safetyStockLevel'],
                'ReorderPoint' => $_POST['reorderPoint'],
                'DaysToManufacture' => $_POST['daysToManufacture'],
                'Class' => $_POST['class'],
                'SellStartDate' => $_POST['sellStartDate'],
                'rowguid' => $_POST['rowguid'],
                'ModifiedDate' => $_POST['modifiedDate']
            ];

            try{
                $this->pdo->beginTransaction(); 

                $stmt = $this->pdo->prepare("INSERT INTO product (Name, ProductNumber, MakeFlag, Color, SafetyStockLevel, ReorderPoint, DaysToManufacture, Class, SellStartDate, rowguid, ModifiedDate) VALUES (:Name, :ProductNumber, :MakeFlag, :Color, :SafetyStockLevel, :ReorderPoint, :DaysToManufacture, :Class, :SellStartDate, :rowguid, :ModifiedDate)");

                $stmt->execute($addData);
                $this->pdo->commit();

                header('Location:'.WEBROOT.'/');
            }
            catch(PDOException $e){ 
                $this->pdo->rollBack();
                echo "Erreur de requête : " . $e->getMessage();
            }
        }
    }

    public function deleteProduct($id){
        // ça tombe bien, pas besoin de récupérer des données avec DELETE !

        if(!is_numeric($id)){ 
            header('Location:'.WEBROOT.'/400');
        }
        try{
            $this->pdo->beginTransaction(); 

            $stmt = $this->pdo->prepare("DELETE FROM product WHERE ProductID = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->pdo->commit();

            header('Location:'.WEBROOT.'/');
        }
        catch(PDOException $e){ 
            $this->pdo->rollBack();
            echo "Erreur de requête : " . $e->getMessage();
        }
    }
}