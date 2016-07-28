<?php 

$login = new AuthLogin();
if($login->checkUserLogin()){
	$title = 'Categories | E-System';
	require $app_root . '/includes/admin/header.php';
	$connect = Connect::connectTo();
	$connect->sqlQuery("
		SELECT category_name, category_id 
		FROM categories
		ORDER BY category_id ASC
	");
	$connect->executeQuery();
	$names = $connect->resultSet();
	
	$img = [
		$dir_img . 'icons/medal.png',
		$dir_img . 'icons/trophy.png'
	];
}else {
	$login->blankLogin($base_url . 'index.php');
}
?>
	<header class="user-side" id="profile">
		<img class="user-side-icon" src="<?php echo $dir_img;?>icons/user.png">
		<div class="user-side-txt">
			<a class="container-a" href="logout.php"><?php echo $_SESSION['user']['combined_name'];?></a>
		</div>
	</header>
	<section class="container-main">
		<h2 class="container-h" align="center">Categories</h2>
		<ul class="container-list">
			<?php foreach($names as $nam):?>
				<li class="list-item-cards">
					<div class="list-item-circular">
						<a href="vote.php?id=<?php echo $nam['category_id'];?>">
							<img class="list-item-circular-img" src="<?php echo $img[rand(0, count($img)-1)];?>">
						</a>
					</div>
					<div class="list-item-txt"><?php echo htmlentities($nam['category_name'], ENT_QUOTES, 'UTF-8');?></div>
				</li>
			<?php endforeach;?>
		</ul>
	</section>
	<script src="<?php echo $dir_js;?>scroll.js"></script>
<?php require $app_root . '/includes/admin/footer.php';?>