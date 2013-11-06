<div id="site-content">

	<div id="header-content">
	
		<div id="site-title">
			<h1><a href="http://p2.bazaarghat.com/"><?php echo APP_NAME; ?></a></h1>
		</div>
		
		<div id="greet">
			Welcome, <?php echo $_SESSION['first_name']; ?> | <a href="/user/logout">Sign Out</a>
		</div>
		
		<div id="nav-menu">
			<ul>				
				<li>
					<a href="/user/post" title="My posts" <?php echo ( $page == 'post') ? 'class="current"' : '' ?>>My Posts</a>
				</li>
				
				<li>
					<a href="/user/stream" title="Post stream" <?php echo ( $page == 'stream') ? 'class="current"' : '' ?>>Post Stream</a>
				</li>
				
				<li>
					<a href="/user/manage" title="Manage Fellow" <?php echo ( $page == 'manage') ? 'class="current"' : '' ?>>Other Moviewers</a>
				</li>
				
				<li>
					<a href="/user/add" title="Add a New Post" <?php echo ( $page == 'add') ? 'class="current"' : '' ?>>New Post</a>
				</li>
			</ul>
		</div>
		
	</div>
	
	<div id="main-content">