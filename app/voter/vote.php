<?php
$login = new AuthLogin();
if($login->checkUserLogin()){
	$title = 'Vote | E-System';
	require $app_root . '/includes/admin/header.php';
	$csrf = new CSRF();
	if(!empty($_GET['id'])){
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$connect = Connect::connectTo();
		$connect->beginTransaction();
		$connect->sqlQuery("
			SELECT category_name
			FROM categories
			WHERE category_id=:id
		");
		$connect->paramBind(':id', $id);
		$connect->executeQuery();
		$name = $connect->singleRow();
		
		$connect->sqlQuery("
			SELECT categories.category_name, nominees.nominee_name, nominees.nominee_img, nominees.nominee_id
			FROM categories 
			JOIN nominees 
			ON categories.category_id = nominees.cat_id
			WHERE category_id = :id
		");
		$connect->paramBind(':id', $id);
		$connect->executeQuery();
		$nominees = $connect->resultSet();
		
		$connect->sqlQuery("
			SELECT  nominees.nominee_id, nominees.nominee_name, votes.cat_id, votes.std_id 
			FROM votes JOIN nominees
			ON nominees.nominee_id = votes.nom_id
			WHERE votes.cat_id = :cat
			AND votes.std_id = :std
		");
		$connect->paramBind(':cat', $id);
		$connect->paramBind(':std', $_SESSION['user']['student_id']);
		$connect->executeQuery();
		$check_user = $connect->totalRows();
		
		if($name && $nominees) {
			$connect->endTransaction();
		}else {
			$connect->cancelTransaction();
		}
	}else {
		$id = false;
	}
}else {
	$login->blankLogin($base_url . 'index.php');
}
?>
	<?php 
		if($id == false){
			header('Location: main.php');
		}
	?>
	<header class="user-side" id="profile">
		<img class="user-side-icon" src="<?php echo $dir_img;?>icons/user.png">
		<div class="user-side-txt">
			<a class="container-a" href="logout.php"><?php echo $_SESSION['user']['combined_name'];?></a>
		</div>
	</header>
	<?php if($check_user > 0):?>
		<h1 align="center">You have already voted.</h1>
	<?php else:?>
		<section class="container-main">
			<h1 align="center"><?php echo htmlentities($name["category_name"], ENT_QUOTES, 'UTF-8');?></h1>
			<form class="container-list" action="cast_vote.php" method="post" enctype="multipart/form-data">
				<?php foreach($nominees as $nom):?>
					<div class="list-item-cards">
						<div class="list-item-circular">
							<img class="list-item-circular-img" src="<?php echo htmlentities('../' . $nom["nominee_img"], ENT_QUOTES, 'UTF-8');?>">
						</div>
						<div class="list-item-txt">
							<input type="radio" name="nominee" value="<?php echo $nom["nominee_id"];?>">
							<?php echo htmlentities($nom["nominee_name"], ENT_QUOTES, 'UTF-8');?>
						</div>
					</div>
				<?php endforeach;?>
				<input type="submit" value="Vote" class="list-item-cards list-item-btn">
				<input type="hidden" name="category" value="<?php echo $id;?>">
				<input type="hidden" name="token" value="<?php echo $csrf->generate();?>">
			</form>
		</section>
	<?php endif;?>
	<script src="<?php echo $dir_js;?>scroll.js"></script>
<?php require $app_root . '/includes/main/footer.php';?>