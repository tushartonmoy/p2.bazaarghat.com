<?php include 'v_user_menu.php'; ?>

<?php
	$i = 0;
	foreach( $data as $row ){
		$image = APP_PATH . "uploads/avatars/" . $row['first_name'] . $row['id'] . ".*";
		$file_exist = glob($image);
		$name = $row['first_name'] . " " . $row['last_name'];
		echo "<div class='user'>";
		echo "<div class='post-image'>";
		if( $file_exist ){
			foreach($file_exist as $myfile){
				$file = basename($myfile);
				echo "<img src='/uploads/avatars/$file' alt='$name' width='40' height='40'/>";
			}
		}else{
			echo "<img src='/uploads/avatars/example.gif' alt='$name' width='40' height='40'/>";
		}
		echo "</div>";
		if( $row['following_id'] != NULL ){
			echo "<div class='user-name following'>" . $row['first_name'] . " " . $row['last_name'] . "</div>";
			echo "<div class='follow-status'>";
			echo "<a href='/user/unfollow/" . $row['id'] . "' class='submit'>Unfollow</a><br />";
		}else{
			echo "<div class='user-name notfollowing'>" . $row['first_name'] . " " . $row['last_name'] . "</div>";
			echo "<div class='follow-status'>";
			echo "<a href='/user/follow/" . $row['id'] . "' class='submit'>Follow</a><br />";
		}
		echo "</div>";
		echo "</div>";
		$i++;
	}
	if( $i == 0 ){
		echo "<p>It seems you are alone here. Tell some of your friends to join you here</p>";
	}
?>