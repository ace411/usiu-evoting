<?php
$login = new AuthLogin();
if($login->checkUserLogin()){
	if(isset($_POST) && !empty($_POST)){
		$token = isset($_POST['token']) ? $_POST['token'] : '';
		$nominee = isset($_POST['nominee']) ? $_POST['nominee'] : '';
		$category = isset($_POST['category']) ? $_POST['category'] : '';
		$csrf = new CSRF();
	
		$_SESSION['token'] = $token;
		if($csrf->check($_SESSION['token'])){
			$connect = Connect::connectTo();
			$connect->sqlQuery("
				INSERT INTO votes(std_id, cat_id, nom_id)
				SELECT :std, :cat, :nom
				FROM categories
				WHERE EXISTS(
					SELECT category_id
					FROM categories
					WHERE category_id = :cat
				)AND EXISTS (
					SELECT nominee_id 
					FROM nominees
					WHERE nominee_id = :nom
				)AND NOT EXISTS (
					SELECT vote_id 
					FROM votes
					WHERE std_id = :std
					AND nom_id = :nom
				)LIMIT 1		
			");
			$connect->paramBind(':std', $_SESSION['user']['student_id']);
			$connect->paramBind(':cat', $category);
			$connect->paramBind(':nom', $nominee);
			$connect->executeQuery();
			header("Location: index.php");
		}else {
			debug_backtrace();
		}
	}		
}
