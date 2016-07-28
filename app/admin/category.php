<?php

$title = 'Add records | Admin';
require $app_root . '/includes/admin/header.php';

$connect = Connect::connectTo();
if(isset($_POST['category']) || !empty($_POST['category'])){
	$category = isset($_POST['category']) ? $_POST['category'] : '';
	$desc = isset($_POST['category_desc']) ? $_POST['category_desc'] : '';
	$connect->sqlQuery(
		'INSERT INTO categories (category_name, category_description)
		VALUES (:cat_name, :cat_desc)'
	);
	$connect->paramBind(':cat_name', $category);
	$connect->paramBind(':cat_desc', $desc);
	$connect->executeQuery();
	header('Location: nominees.php');
}
?>
	<form action="category.php" method="post" enctype="multipart/form-data">
		<h2>Add Category</h2>
		<input type="text" value="" name="category" pattern="[a-zA-Z\s]+" placeholder="Category name" required><br/>
		<textarea name="category_desc" placeholder="Category description (optional)"></textarea><br/>
		<input type="submit" value="Submit">
	</form>
<?php require $app_root . '/includes/admin/footer.php';?>