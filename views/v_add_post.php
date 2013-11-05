<?php include 'v_user_menu.php'; ?>

<h1 class="main-title">Add New Post</h1>

<?php
	if( isset( $_SESSION['error_post'] ) ){
		echo "<div class='error'>" . $_SESSION['error_post'] . "</div>";
	}else if( isset( $_SESSION['success_post'] ) ){
		echo "<div class='success'>" . $_SESSION['success_post'] . "</div>";
	}
?>

<form method="post" action="#">
	<textarea name="post" rows="20" cols="40"></textarea>
	<br />
	<input type="submit" name="submit" value="Shout" class="submit" />
</form>

<?php
	unset( $_SESSION['error_post'] );
	unset( $_SESSION['success_post'] );
?>