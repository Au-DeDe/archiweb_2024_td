<?php

require_once MODELSDIR.'/ProductModel.php';
class PDOController{
    private $pdo;
    public function __construct() {
        $dsn = "mysql:host=localhost;dbname=adwfull;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage();
            die(); 
        }
    }

    public function getAllProducts(): string {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM product");
            $stmt->execute();
    
            // Récupérer les résultats sous forme d'objets Product
            $products = [];
            $counter = 0; // Compteur pour limiter à 10 éléments
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new ProductModel($row['ProductID'], $row['Name'], $row['ProductNumber'], $row['MakeFlag'],
                    $row['Color'], $row['SafetyStockLevel'], $row['ReorderPoint'], $row['DaysToManufacture'], $row['Class'],
                    $row['SellStartDate'], $row['ModifiedDate']);
                $products[] = $product;
                $counter++;
                if ($counter >= 10) {
                    break; // Sortir de la boucle après 10 éléments
                }
            }
            
            $jsonProducts = json_encode($products);
    
            if ($jsonProducts === false) {
                throw new Exception("Erreur lors de l'encodage des produits en JSON.");
            }
    
            return $jsonProducts;
        } catch (PDOException $e) {
            // En cas d'erreur de requête, afficher le message d'erreur
            echo "Erreur de requête : " . $e->getMessage();
            return json_encode([]); // Retourner un tableau vide encodé en JSON en cas d'erreur
        } catch (Exception $e) {
            // En cas d'erreur lors de l'encodage JSON, afficher le message d'erreur
            echo "Erreur lors de l'encodage JSON : " . $e->getMessage();
            return json_encode([]); // Retourner un tableau vide encodé en JSON en cas d'erreur
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
                $row['SellStartDate'], $row['ModifiedDate']);

            $jsonProduct = json_encode($product);

            var_dump($jsonProduct);
    
            if ($jsonProduct === false) {
                throw new Exception("Erreur lors de l'encodage des produits");
            }
            return $jsonProduct;
        } catch (PDOException $e) {
            // En cas d'erreur de requête, afficher le message d'erreur
            echo "Erreur de requête : " . $e->getMessage();
            return json_encode([]); 
        } catch (Exception $e) {
            echo "JSON : " . $e->getMessage();
            return json_encode([]); 
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
                'ModifiedDate' => $decodedData['modifiedDate']
            ];
        }
        try{

            $this->pdo->beginTransaction(); 

            $stmt = $this->pdo->prepare("UPDATE product SET Name = :name, ProductNumber = :productNumber, MakeFlag = :makeFlag, 
            Color = :color, SafetyStockLevel = :safetyStockLevel, ReorderPoint = :reorderPoint, DaysToManufacture = :daysToManufacture, 
            Class = :class, SellStartDate = :sellStartDate, ModifiedDate = :modifiedDate WHERE ProductID = :id");

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
        if(isset($_POST['name']) && isset($_POST['productNumber']) && isset($_POST['makeFlag']) && isset($_POST['color']) && isset($_POST['safetyStockLevel']) && isset($_POST['reorderPoint']) && isset($_POST['daysToManufacture']) && isset($_POST['class']) && isset($_POST['sellStartDate']) && isset($_POST['modifiedDate'])){
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
                'ModifiedDate' => $_POST['modifiedDate']
            ];

            try{
                $this->pdo->beginTransaction(); 

                $stmt = $this->pdo->prepare("INSERT INTO product (Name, ProductNumber, MakeFlag, Color, SafetyStockLevel, ReorderPoint, DaysToManufacture, Class, SellStartDate, ModifiedDate) VALUES (:Name, :ProductNumber, :MakeFlag, :Color, :SafetyStockLevel, :ReorderPoint, :DaysToManufacture, :Class, :SellStartDate, :ModifiedDate)");

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