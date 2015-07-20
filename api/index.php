<?php
header('Content-Type: text/html; charset=utf-8');
define('ROOT',dirname(__FILE__));
define('DS',DIRECTORY_SEPARATOR);

require 'Library'.DS.'Core'.DS.'AutoLoad.php';

$autoLoad = new AutoLoad();
$autoLoad->setPath(ROOT);
$autoLoad->setExt('php');

spl_autoload_register(array($autoLoad, 'loadCore'));
spl_autoload_register(array($autoLoad, 'loadCoreApp'));
spl_autoload_register(array($autoLoad, 'load'));

$request = new Request();
$route = new Route($request);
