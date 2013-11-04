<?php include 'v_signin_menu.php'; ?>
<h1 class="main-title">Signup</h1>

<form method="post" action="#" enctype="multipart/form-data">
	<?php
		if( isset($_SESSION['error_first_name']) ){
			echo "<div class='error'>" . $_SESSION['error_first_name'] . "</div>";
		}else if( isset($_SESSION['error_last_name']) ){
			echo "<div class='error'>" . $_SESSION['error_last_name'] . "</div>";
		}else if( isset($_SESSION['error_email']) ){
			echo "<div class='error'>" . $_SESSION['error_email'] . "</div>";
		}else if( isset($_SESSION['error_password']) ){
			echo "<div class='error'>" . $_SESSION['error_password'] . "</div>";
		}else if( isset($_SESSION['error_file']) ){
			echo "<div class='error'>" . $_SESSION['error_file'] . "</div>";
		}
	?>
	<label for="first_name" class="option-title">First Name:</label><br />
	<input type="text" name="first_name" id="first_name" value="<?php if( (isset($_POST['first_name'])) && (!isset($_SESSION['error_first_name'])) ) echo $_POST['first_name']; ?>"/>
	<br /><br />
	
	<label for="last_name" class="option-title">Last Name:</label><br />
	<input type="text" name="last_name" id="last_name" value="<?php if( (isset($_POST['last_name'])) && (!isset($_SESSION['error_last_name'])) ) echo $_POST['last_name']; ?>"/>
	<br /><br />
	
	<label for="email" class="option-title">Email:</label><br />
	<input type="text" name="email" id="email" value="<?php if( (isset($_POST['email'])) && (!isset($_SESSION['error_email'])) ) echo $_POST['email']; ?>"/>
	<br /><br />
	
	<label for="password" class="option-title">Password:</label><br />
	<input type="password" name="password" id="password"/>
	<br /><br />
	
	<label for="avatar" class="option-title">Upload Avatar:</label>
	<input type="file" name="avatar" id="avatar"/>
	<br />
	
	<input type="submit" name="submit" value="Sign Up" class="submit" />
</form>
<?php
	unset($_SESSION['error_first_name']);
	unset($_SESSION['error_last_name']);
	unset($_SESSION['error_email']);
	unset($_SESSION['error_password']);
	unset($_SESSION['error_file']);
?>