<?php 

$title = 'Add nominees | Admin';
require $app_root . '/includes/admin/header.php';
$connect = Connect::connectTo();
$connect->sqlQuery("
	SELECT category_name, category_id 
	FROM categories
");
$connect->executeQuery();
$rows = $connect->resultSet();

if(isset($_POST['nominee_name'], $_POST['category'], $_FILES['file']) || !empty($_POST) || !empty($_FILES)){
	$file = isset($_FILES['file']) ? $_FILES['file'] : false;
	$name = isset($_POST['nominee_name']) ? $_POST['nominee_name'] : '';
	$category = isset($_POST['category']) ? $_POST['category'] : '';
	$description = isset($_POST['nominee_desc']) ? $_POST['nominee_desc'] : '';
		
	$images = new Images();
	$upload = $images->uploadImage($file);
	$connect->sqlQuery('
		INSERT INTO nominees (nominee_name, nominee_desc, nominee_img, cat_id)
		VALUES(:name, :desc, :img, :cat)
	');
	$connect->paramBind(':name', $name);
	$connect->paramBind(':desc', $description);
	$connect->paramBind(':img', $upload);
	$connect->paramBind(':cat', $category);
	$connect->executeQuery();
	echo 'Added';
}

?>
	<form action="nominees.php" method="post" enctype="multipart/form-data">
		<input type="text" name="nominee_name" placeholder="Nominee name" required><br>
		<input type="text" name="nominee_desc" placeholder="Description" ><br />
		<select name="category">
			<?php foreach($rows as $r):?>
				<option value="<?php echo htmlentities($r['category_id'], ENT_QUOTES, 'UTF-8');?>">
					<?php echo htmlentities($r['category_name'], ENT_QUOTES, 'UTF-8');?>	
				</option>
			<?php endforeach;?>
		</select><br />
		<input type="file" name="file">
		<input type="submit" value="submit">
	</form>	
<?php require $app_root . '/includes/admin/footer.php';?>