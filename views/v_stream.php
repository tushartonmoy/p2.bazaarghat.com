<?php include 'v_user_menu.php'; ?>

<h1 class="main-title">Post Stream</h1>
<?php
	$i = 0;
	foreach( $data as $row ){
		echo "<div class='post'>";
		echo "<div class='post-image'>";
		$image = APP_PATH . "uploads/avatars/" . $row['first_name'] . $row['user_id'] . ".*";
		$file_exist = glob($image);
		$name = $row['first_name'] . " " . $row['last_name'];
		if( $file_exist ){
			foreach($file_exist as $myfile){
				$file = rawurlencode(basename($myfile));
				echo "<img src='/uploads/avatars/$file' alt='$name' width='40' height='40'/>";
			}
		}else{
			echo "<img src='/uploads/avatars/example.gif' alt='$name' width='40' height='40'/>";
		}
		echo "</div>";
		echo "<div class='post-content'>";
		echo "<div class='post-user'>";
		echo $row['first_name'] . " " . $row['last_name'];
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
		echo "<div class='option-title'>No activity. Follow some friends to see their updates.</div>";
	}