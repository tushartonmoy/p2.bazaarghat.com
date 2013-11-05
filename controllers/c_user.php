<?php

class user_controller extends base_controller {

	/*-------------------------------------------------------------------------------------------------

	-------------------------------------------------------------------------------------------------*/
	public function __construct() {
			parent::__construct();
		
		# If user is not signed in, redirect to the homepage for sign in
			if( !isset($_SESSION['is_user_logged_in'] ) ){
				header("Location:/");
			}
			$this->db = DB::instance(DB_NAME);
	}
	
	/*-------------------------------------------------------------------------------------------------
	Redirect user to the Stream page
	-------------------------------------------------------------------------------------------------*/
	public function index() {
		
		# People will redirect to the stream page
			header("Location:/user/stream/");
			die();

	} # End of index method
	
	/*-------------------------------------------------------------------------------------------------
	Show all the post made by user
	-------------------------------------------------------------------------------------------------*/
	public function post(){
			$user_id = $_SESSION['user_id'];
			$sql = "SELECT * FROM post WHERE user_id = $user_id ORDER BY time DESC";
		
		# Set template variables and load template
			$this->template->set_global('data', $this->db->select_rows($sql));
			$this->template->content = View::instance('v_post');
			$this->template->title = "My Posts";
			$this->template->set_global('page', 'post');
			echo $this->template;
	} # End of post method
	
	/*-------------------------------------------------------------------------------------------------
	Replaces <br /> with \n. Private function. Only used within other function
	-------------------------------------------------------------------------------------------------*/
	private function br2nl($text){
		return  preg_replace('/<br\\s*?\/??>/i', '', $text);
	}
	
	/*-------------------------------------------------------------------------------------------------
	Edit a of User
	-------------------------------------------------------------------------------------------------*/
	public function edit($id){
		# If $_POST['submit'] is set, user has submitted the form after editing
			if( isset( $_POST['submit'] ) ){
				# Get the submitted values
					$post = trim( $_POST['post'] );
				
				# If post body is empty, set error flag
					if( empty( $post ) ){
						$_SESSION['error_post'] = "Post can't be empty";
					}else{
					# Update the post and redirect to my post page
						$data = array(	"post"		=>	nl2br($post),
										"time"		=>	date("Y-m-d H:i:s", time())
									);
						$table = 'post';
						$where_condition = "WHERE id = $id";
						$id = $this->db->update_row('post', $data, $where_condition);
						header("Location:/user/post");
						die();
				}
			}
			# Form is not submitted or there is error
				$user_id = $_SESSION['user_id'];
				$sql = "SELECT post FROM post WHERE id = $id AND user_id = $user_id";
				$result = $this->db->select_row($sql);
			
			# If the post Id is invalid, redirect user, otherwise show the content of page
				if( !$result ){
					header("Location:/user/post");
					die();
				}else{
					$this->template->set_global('data', $this->br2nl($result['post']));
					$this->template->set_global('page', 'edit');
					$this->template->content = View::instance('v_edit');
					$this->template->title = "Edit Post";
					echo $this->template;
				}
	} # End of edit method
	
	/*-------------------------------------------------------------------------------------------------
	Delete a post of User
	-------------------------------------------------------------------------------------------------*/
	public function delete($id){
			$user_id = $_SESSION['user_id'];
			$id = $this->db->sanitize($id);
			$table = "post";
			$where_condition = "WHERE id = $id AND user_id = $user_id";
			$this->db->delete($table, $where_condition);
			header("Location:/user/post");
			die();
	} # End of delete method
	
	/*-------------------------------------------------------------------------------------------------
	Show all the post of people user is following
	-------------------------------------------------------------------------------------------------*/
	public function stream(){
			$user_id = $_SESSION['user_id'];
		
		# SQL query to select all post from following user
			$sql = "SELECT post.* , user_info.first_name, user_info.last_name FROM  `post` ";
			$sql .= "RIGHT JOIN follow ON ( post.user_id = follow.following_id AND follow.user_id =$user_id ) ";
			$sql .= "RIGHT JOIN user_info ON post.user_id = user_info.id WHERE post.user_id !=$user_id ";
			$sql .= "ORDER BY post.time DESC";
		
		# Set template variable and load template file
			$this->template->set_global('data', $this->db->select_rows($sql));
			$this->template->content = View::instance('v_stream');
			$this->template->title = "Post Stream";
			$this->template->set_global('page', 'stream');
			echo $this->template;
	} # End of stream method
	
	/*-------------------------------------------------------------------------------------------------
	Show list of all user with follow/unfollow option
	-------------------------------------------------------------------------------------------------*/
	public function manage(){
			$user_id = $_SESSION['user_id'];
		
		# SQL query to get all the user info
			$sql = "SELECT user_info.id, user_info.first_name, user_info.last_name, follow.following_id ";
			$sql .= "FROM user_info LEFT OUTER JOIN  `follow` ON (user_info.id = follow.following_id AND ";
			$sql .= "follow.user_id=$user_id) WHERE user_info.id !=$user_id";
			$users = $this->db->select_rows($sql);
		
		# Set template variables and load template file
			$this->template->set_global('data', $users);
			$this->template->content = View::instance('v_manage');
			$this->template->title = "Manage User";
			$this->template->set_global('page', 'manage');
			echo $this->template;
	} # End of manage method
	
	/*-------------------------------------------------------------------------------------------------
	Follow a user
	-------------------------------------------------------------------------------------------------*/
	public function follow($following_id){
			$table = 'follow';
			$data = array(	"user_id"	=>	$_SESSION['user_id'],
							"following_id"	=>	$following_id
						);
		
		# Using mysqli connection
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($mysqli->connect_errno) {
				echo "Something went wrong. Try again later. If error persists, contact admininstrator.";
				die();
			}
			$sql = "INSERT INTO follow(user_id, following_id) VALUES(?,?)";
			$stmt = $mysqli->prepare($sql);
			if($stmt === false) {
			  trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $mysqli->error, E_USER_ERROR);
			}
			
		# Update database
			$stmt->bind_param("dd",$val1,$val2);
			$val1 = $data['user_id'];
			$val2 = $data['following_id'];
			$result = $stmt->execute();
			header("Location:/user/manage");
			die();
	} # End of follow method
	
	/*-------------------------------------------------------------------------------------------------
	Unfollow a user
	-------------------------------------------------------------------------------------------------*/
	public function unfollow($following_id){
			$user_id = $_SESSION['user_id'];
			$following_id = $this->db->sanitize($following_id);
			$table = 'follow';
			$where_condition = "WHERE user_id = $user_id AND following_id = $following_id";
			$this->db->delete($table, $where_condition);
			header("Location:/user/manage");
			die();
	} # End of unfollow method
	
	/*-------------------------------------------------------------------------------------------------
	Add new post
	-------------------------------------------------------------------------------------------------*/	
	public function add(){
		#If $_POST['submit'] is set, user has submitted form
			if( isset( $_POST['submit'] ) ){
				$post = trim($_POST['post']);
				
			# If post is empty set error flag, otherwise add in database and set flag
				if( empty( $post ) ){
					$_SESSION['error_post'] = "Please Enter your post";
				}else{
					$data = array(	"user_id"	=>	$_SESSION['user_id'],
									"post"		=>	nl2br($post),
									"time"		=>	date("Y-m-d H:i:s", time())
								);
					$id = $this->db->insert_row('post', $data);
					$_SESSION['success_post'] = "Post added. Add another one?";
				}
			}
			
			# set template variables and load template file
			$this->template->content = View::instance('v_add_post');
			$this->template->title = "Add a New post";
			$this->template->set_global('page', 'add');
			echo $this->template;
	} # End of add method
	
	/*-------------------------------------------------------------------------------------------------
	User is signed out. Destroy session and redirect to homepage.
	-------------------------------------------------------------------------------------------------*/
	public function logout(){
			session_destroy();
			header("Location:/");
	}
	
} # End of class