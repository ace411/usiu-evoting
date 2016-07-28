<?php 

$title = 'Home | Campus Awards';
require $app_root . '/includes/main/header.php';
$login = new AuthLogin();
$csrf = new CSRF();
if(isset($_POST) && !empty($_POST)){
	$sch_id = isset($_POST['school_id']) ? $_POST['school_id'] : '';
	$pass = isset($_POST['password']) ? $_POST['password'] : '';
	$token = isset($_POST['token']) ? $_POST['token'] : '';
	$_SESSION['token'] = $token;
	
	if($csrf->check($_SESSION['token'])){
		$processLogin = $login->logUserIn($sch_id, $pass);
		if($processLogin){
			header("Location: voter/index.php");
		}else {
			print("
				<div class='error'>
					Error with login. 
				</div>
			");
		}	
	}
}
?>
	<section class="container-nofrm">
		<div class="nofrm-circular">
			<img class="nofrm-circular-img" src="<?php echo $img_url;?>icons/check.png">
		</div>
		<div class="nofrm-content">
			Please login to vote. Remember to be mindful of the constraints.
		</div>
	</section>
	<section class="container-frm">
		<form action="index.php" method="post" class="container-frm-form" enctype="multipart/form-data">
			<h2 align="center">Vote</h2>
			<input type="text" class="container-form-input" name="school_id" value="" placeholder="School ID" maxlength="6" minlength="6" required>
			<input type="password" class="container-form-input" name="password" placeholder="Password" required>
			<input type="submit" class="container-form-btn" value="Login">
			<input type="hidden" name="token" value="<?php echo $csrf->generate();?>">
			<p><a class="container-a" href="admin/register.php">Register</a></p>
		</form>
	</section>	
<?php require $app_root . '/includes/main/footer.php';?>