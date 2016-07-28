<?php

spl_autoload_register(function($className){
	$view_files = htmlspecialchars(dirname(__DIR__));
	$view_files = str_replace("\\", "/", $view_files);
	$objects = $view_files."/objects";
	require $objects . "/" . $className.".php";
});