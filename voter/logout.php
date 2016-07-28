<?php

$dir = str_replace('\\', '/', dirname(__DIR__));

include $dir . '/app/start.php';

$login = new AuthLogin();
if($login->checkUserLogin()){
	$connect = Connect::connectTo();
	$connect->sqlQuery("
		UPDATE students 
		SET has_voted = 1
		WHERE student_id = :id
	");
	$connect->paramBind(':id', $_SESSION['user']['student_id']);
	$connect->executeQuery();
	$login->logUserOut();
	$login->userLogout('../index.php');
}