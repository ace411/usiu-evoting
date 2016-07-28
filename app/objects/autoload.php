<?php

/*
 *Autoload the classes in the objects folder
 *No namespaces are loaded along with the files
 *PSR-4-compliant system to follow soon
*/

spl_autoload_register(function($className){
	$view_files = htmlspecialchars(dirname(__DIR__));
	$view_files = str_replace("\\", "/", $view_files);
	$objects = $view_files."/objects";
	require $objects . "/" . $className.".php";
});

/*
 Designed by Lochemem Bruno Michael
*/
