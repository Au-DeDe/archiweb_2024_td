<?php

final class HomeController{
    public function index() {
        // page index

        require VIEWSDIR.DS.'homeView.php';
        $homeView = new HomeView();
        echo $homeView->homepage();
    }
}