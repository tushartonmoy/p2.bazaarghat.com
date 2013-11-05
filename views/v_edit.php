<?php include 'v_user_menu.php'; ?>

<h1 class="main-title">Edit Post</h1>

<?php
	if( isset( $_SESSION['error_post'] ) ){
		echo "<div class='error'>" . $_SESSION['error_post'] . "</div>";
	}
?>

<form method="post" action="#">
	<textarea name="post" rows="20" cols="40" ><?php echo $data; ?></textarea>
	<br />
	<input type="submit" name="submit" value="Save" class="submit" />
</form>

<?php
	unset( $_SESSION['error_post'] );