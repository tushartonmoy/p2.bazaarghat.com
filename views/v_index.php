<?php include 'v_signin_menu.php'; ?>
<div id="site-left">

	<h1 class="main-title">Movie Review Site</h1>
	<p>Welcome to Movie review site. Post your thought about the movies you have seen. Follow and find out what people are watching...
	<br/><br/>
	You can edit and delete reviews.</p>
	<p style="margin: 10px 0;"><a href="/signup" title="Sign up" class="submit">Sign up</a></p>
	
</div>

<div id="site-right">
	<div class="main-title">Sign In</div>
	<?php
		if( isset($_SESSION['error_email']) ){
			echo "<div class='error'>" . $_SESSION['error_email'] . "</div>";
		}else if( isset($_SESSION['error_password']) ){
			echo "<div class='error'>" . $_SESSION['error_password'] . "</div>";
		}else if( isset($_SESSION['error_login']) ){
			echo "<div class='error'>" . $_SESSION['error_login'] . "</div>";
		}else if( isset($_SESSION['error_duplicate']) ){
			echo "<div class='error'>" . $_SESSION['error_duplicate'] . "</div>";
			unset($_SESSION['error_duplicate']);
		}else if( isset($_SESSION['success'] ) ){
			echo "<div class='success'>" . $_SESSION['success'] . "</div>";
		}
	?>
	<form method="post" action="/index/signin">
		<label for="email" class="option-title">Email Address:</label><br />
		<input type="text" name="email" id="email" value="<?php if( isset($_SESSION['email']) ) { echo $_SESSION['email']; unset($_SESSION['email']); } ?>"/>
		<br /><br />
		
		<label for="password" class="option-title">Password:</label><br />
		<input type="password" name="password" id="password"/>
		<br /><br />
		<input type="submit" name="submit" value="Sign in" class="submit" />
	</form>

	<?php
		unset( $_SESSION['error_email'] );
		unset( $_SESSION['error_password'] );
		unset( $_SESSION['error_login'] );
		unset( $_SESSION['success'] );
	?>
</div>