<?php

class homeView{
    public function homepage(){
        ob_start();
        $ressource = 'home';
        $methode = 'homepage';
        require VIEWSDIR.DS.'template.php';
        $html = ob_get_clean();

        return $html;
    }
}