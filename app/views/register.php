<?php
$title = 'Register | Campus Awards';
require $app_root . '/includes/admin/header.php';
$valid = new Validate();
$csrf = new CSRF();
if(!empty($_POST) && isset($_POST)){
	$fname = isset($_POST['firstname']) ? stripslashes($_POST['firstname']) : '';
	$lname = isset($_POST['lastname']) ? stripslashes($_POST['lastname']) : '';
	$sch_id = isset($_POST['id_number']) ? stripslashes($_POST['id_number']) : '';
	$pass = isset($_POST['password']) ? stripslashes($_POST['password']) : '';
	$retype = isset($_POST['retype']) ? stripslashes($_POST['retype']) : '';
	$token = isset($_POST['token']) ? stripslashes($_POST['token']) : '';
	$valid->setType(false);	
	
	$check_pass = $valid->retypePass($pass, $retype);
	$valid_pass = $valid->checkPass($pass);
	$check_id = $valid->validateNum($sch_id);
	$check_id_len = $valid->strLength($sch_id, 6);
	$check_fname = $valid->validateText($fname);
	$check_lname = $valid->validateText($lname);
	
	$_SESSION['token'] = $token;
	if($csrf->check($_SESSION['token'])){
		if(
			$check_pass &&
			$check_id &&
			$check_id_len &&
			$check_fname &&
			$check_lname &&
			$valid_pass	
		){
			$login = new AuthLogin();
			$register = $login->createUser($sch_id, $pass, $fname, $lname);
			if($register == false){
				header('Location: ../index.php');
			}else {
				echo "
					<div class='error'>
						Error with registration.<br/>
					</div>
				";
			}
		}	
	}	
}else {
	$fname = false;
	$lname = false;
	$retype = false;
	$pass = false;
	$token = false;
	$login = false;
}
?>
	<section class="container-nofrm">
		<div class="nofrm-circular">
			<img class="nofrm-circular-img" src="<?php echo $dir_img;?>icons/user.png">
		</div>
		<div class="nofrm-content">
			Register a voter. This is the Electronic Voting System.
		</div>
	</section>
	<section class="container-frm">
		<form class="container-frm-form container-frm-register" action="register.php" class="container-form" method="post" enctype="multipart/form-data">
			<h2 align="center">Details Please</h2>
			<input type="text" class="container-form-input" name="firstname"  pattern="[a-z\A-Z]+" placeholder="Firstname" required>
			<input type="text" class="container-form-input" name="lastname"  pattern="[a-z\A-Z]+" placeholder="Lastname" required>
			<input type="number" class="container-form-input" name="id_number"  placeholder="School ID" min="600000" step="1" minlength="6" maxlength="6" required>
			<input type="text" class="container-form-input" value="<?php echo $valid->pwdGenerator(8);?>" readonly>
			<input type="password" class="container-form-input" name="password" placeholder="Password" required>
			<input type="password" class="container-form-input" name="retype" placeholder="Retype password" required>
			<input type="submit" class="container-form-btn" value="Register">
			<input type="hidden" value="<?php echo $csrf->generate();?>" name="token">
		</form>
	</section>	
<?php require $app_root . '/includes/admin/footer.php';?>