<?php
class ProductView{
    public function listall($products){
        ob_start();
        $ressource = 'product';
        $methode = 'listall';
        require VIEWSDIR.DS.'template.php';
        $html = ob_get_clean();

        return $html;
    }

    public function listone($product){
        ob_start();
        $ressource = 'product';
        $methode = 'listone';
        require VIEWSDIR.DS.'template.php';
        $html = ob_get_clean();

        return $html;
    }  
}