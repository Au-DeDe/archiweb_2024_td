<?php
class ProductModel{
    public $ProductID;
    public $Name;
    public $ProductNumber;
    public $MakeFlag;
    public $Color;
    public $SafetyStockLevel;
    public $ReorderPoint;
    public $DaysToManufacture;
    public $Class;
    public $SellStartDate;
    public $ModifiedDate;

    public function __construct($ProductID, $Name, $ProductNumber, $MakeFlag, $Color, $SafetyStockLevel, $ReorderPoint, $DaysToManufacture, $Class, $SellStartDate, $ModifiedDate) {
        $this->ProductID = $ProductID;
        $this->Name = $Name;
        $this->ProductNumber = $ProductNumber;
        $this->MakeFlag = $MakeFlag;
        $this->Color = $Color;
        $this->SafetyStockLevel = $SafetyStockLevel;
        $this->ReorderPoint = $ReorderPoint;
        $this->DaysToManufacture = $DaysToManufacture;
        $this->Class = $Class;
        $this->SellStartDate = $SellStartDate;
        $this->ModifiedDate = $ModifiedDate;
    }
}