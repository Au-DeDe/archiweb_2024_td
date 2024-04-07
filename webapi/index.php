<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define("CLASSDIR", ROOT . DS . 'src');
define("CONTROLLERSDIR", CLASSDIR . DS . 'controllers');
define("MODELSDIR", CLASSDIR . DS . 'models');
define("VIEWSDIR", CLASSDIR . DS . 'Views');
define('WEBROOT', 'http://' .$_SERVER['SERVER_NAME'].(($_SERVER['SERVER_PORT'] == '80')?'':':'.$_SERVER['SERVER_PORT']).((dirname($_SERVER['SCRIPT_NAME']) == DS)?'':dirname($_SERVER['SCRIPT_NAME'])) );

require_once 'Routeur.php';

$r = new Routeur(); // Instanciation du routeur
$r->manageRequest(); // Appel de la méthode manageRequest() du routeur
