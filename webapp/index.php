<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define("CLASSDIR", ROOT . DS . 'src');
define("CONTROLLERSDIR", CLASSDIR . DS . 'controllers');
define("MODELSDIR", CLASSDIR . DS . 'models');
define("VIEWSDIR", CLASSDIR . DS . 'Views');
define('WEBROOT', 'http://localhost/archiweb_2024_tds_gr2_killian.diot'); 

require_once 'Routeur.php';

$r = new Routeur(); // Instanciation du routeur
$r->manageRequest(); // Appel de la méthode manageRequest() du routeur
