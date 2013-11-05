<?php include 'v_user_menu.php'; ?>

<h1 class="main-title">Posts</h1>
<?php
	$name = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
	$image = APP_PATH . "uploads/avatars/" . $_SESSION['first_name'] . $_SESSION['user_id'] . ".*";
	$file_exist = glob($image);
	if( $file_exist ){
		foreach($file_exist as $myfile){
			$file = basename($myfile);
			$my_image = "<img src='/uploads/avatars/$file' alt=' width='40' height='40'/>";
		}
	}else{
		$my_image = "<img src='/uploads/avatars/example.gif' alt='$name' width='40' height='40'/>";
	}
	$i = 0;
	foreach( $data as $row ){
		echo "<div class='post'>";
		echo "<div class='post-image'>";
		echo $my_image;
		echo "</div>";
		echo "<div class='post-content'>";
		echo "<div class='post-top'>";
		echo "<div class='post-user'>";
		echo $_SESSION['first_name'] . " " . $_SESSION['last_name'];
		echo "</div>";
		echo "<div class='post-option'>";
		echo " <a href='/user/edit/" . $row['id'] . "'>Edit</a> | ";
		echo " <a href='/user/delete/" . $row['id'] . "'>Delete</a>";
		echo "</div>";
		echo "</div>";
		echo "<div class='post-text'>";
		echo $row['post'];
		echo "</div>";
		echo "<div class='post-time'>";
		echo date("F j, Y g:i a", strtotime($row['time']));
		echo "</div>";
		echo "</div>";
		echo "</div>";
		$i++;
	}
	if( $i == 0 ){
		echo "<div class='option-title'>Nothing to see here. Add some post <a href='/user/add'>here</a></div>";
	}