<?php

class index_controller extends base_controller {
	
	/*-------------------------------------------------------------------------------------------------

	-------------------------------------------------------------------------------------------------*/
	public function __construct() {
			parent::__construct();
			
		# If user is signed in, redirect them to User panel
			if( isset( $_SESSION['is_user_logged_in'] )){
				header("Location:/user");
			}
			$this->db = DB::instance(DB_NAME);
	}
		
	/*-------------------------------------------------------------------------------------------------
	This is the method for homepage. Shows the welcome message and Sign In form
	-------------------------------------------------------------------------------------------------*/
	public function index() {
			$this->template->content = View::instance('v_index');
			$this->template->title = "Moviews";
			$this->template->set_global('page', 'index');
			echo $this->template;

	} # End of index method
	
	/*-------------------------------------------------------------------------------------------------
	This is for user signup
	A route has been used to access it via http://mydomain.com/signup
	-------------------------------------------------------------------------------------------------*/
	public function signup() {
		# If $_POST['submit'] is set, form has been submitted
			if( isset($_POST['submit'])){
			# Get the submitted Values
				$error_num = 0;
				$first_name = trim($_POST['first_name']);
				$last_name = trim($_POST['last_name']);
				$email = trim($_POST['email']);
				$password = trim($_POST['password']);
			
			# Check for errors in all the fields
			# If First Name is empty, Set error flag
				if( empty($first_name) ){
					$_SESSION['error_first_name'] = "Please enter your First Name";
					$error_num++;
				}
			
			# If Last Name is empty, set error flag
				if( empty($last_name) ){
					$_SESSION['error_last_name'] = "Please enter your Last Name";
					$error_num++;
				}
			
			# If Email field is empty or invalid, set error flag
				if( empty($email) ){
					$_SESSION['error_email'] = "Please enter your Email";
					$error_num++;
				}else if( !filter_var($email, FILTER_VALIDATE_EMAIL) ){
					$_SESSION['error_email'] = "Please enter a valid Email Address";
					$error_num++;
				}
			
			# If Password field is empty or not 5 characters long, set error
				if( empty($password) ){
					$_SESSION['error_password'] = "Please enter your Password";
					$error_num++;
				}else if( strlen($password) < 5 ){
					$_SESSION['error_password'] = "Password must be at least 5 characters long";
					$error_num++;
				}
			
			# If Avatar is not set, set image as false
				if ( empty( $_FILES['avatar']['name'] ) ){
					$image = false;
				}else{
				#Set allowed file extension
					$allowedExts = array("gif", "jpeg", "jpg", "png");
					$temp = explode(".", $_FILES['avatar']["name"]);
					$extension = strtolower(end($temp));
					if ((	($_FILES['avatar']['type'] == "image/gif")
							|| ($_FILES['avatar']['type'] == "image/jpeg")
							|| ($_FILES['avatar']['type'] == "image/jpg")
							|| ($_FILES['avatar']['type'] == "image/pjpeg")
							|| ($_FILES['avatar']['type'] == "image/x-png")
							|| ($_FILES['avatar']['type'] == "image/png")
						)
							&& in_array($extension, $allowedExts)
						){
						# Check if there is any error in file or it exceeds maximum allowed size
						if ($_FILES['avatar']['error'] > 0){
							$_SESSION['error_file'] = "Corruped File. Please try another Image";
							$error_num++;
						}else if ( $_FILES['avatar']['size'] > 5242880 ){
							$_SESSION['error_file'] = "File size exceeded. Max allowed 5 MB.";
							$error_num++;
						}else{
							$image = true;
						}
					}else{
					# The image Doesn't match allowed extension
						$_SESSION['error_file'] = "Invalid File ";
						$error_num++;
					}
				}
			
			# If no error save the data in the database
				if( $error_num == 0){
					$data = array(	"first_name"	=> $first_name,
									"last_name"		=> $last_name,
									"email"			=> $email,
									"password"		=> sha1( $password . $email ),
								);
				
				# Mysqli database connection.
				# Can't use the database class for this as it shows error in duplicate
					$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
					if ($mysqli->connect_errno) {
						$_SESSION['error_file'] = 'Something went wrong. Please try again';
						header("Location: /signup");
						die();
					}
					$sql = "INSERT INTO user_info(first_name, last_name, email, password) VALUES(?,?,?,?)";
					$stmt = $mysqli->prepare($sql);
				
				# Some unkonwn error happened. Set the error field
					if($stmt === false) {
						$_SESSION['error_file'] = 'Something went wrong. Please try again';
						header("Location: /signup");
						die();
					}
				
				# Bind the data for insert
					$stmt->bind_param("ssss",$val1,$val2,$val3, $val4);
					$val1 = $data['first_name'];
					$val2 = $data['last_name'];
					$val3 = $data['email'];
					$val4 = $data['password'];
					$result = $stmt->execute();
				
				# Insertion failed. It can only be because of duplicate email address. Take user to homepage
					if(!$result){
						$_SESSION['error_duplicate'] = "An account already exists with this email address. Please Sign In or create a new account.";
						header("Location:/");
						die();
					}
				
				# Database insert is successful. Get the id of the last inserted row
					$id = $mysqli->insert_id;
				
				# If user uploaded an avatar, Save it in the uploads/avatars/ Folder
					if( $image ){
						$image_name = $data['first_name'] . $id . "." . $extension;
						move_uploaded_file( $_FILES['avatar']['tmp_name'], APP_PATH . "/uploads/avatars/" . $image_name );
					}
				
				# Account creation successful. Redirect to the homepage for Sign in
					$_SESSION['success'] = 'Account created. Please Sign in';
					header("Location:/");
					die();
				}
				
			}
		# User hasn't submitted the Form or has error in the submitted data. Show the Form for sign up
			$this->template->set_global('page', 'signup');
			$this->template->content = View::instance('v_signup');
			$this->template->title = "Signup";	
			echo $this->template;
	} # End of signup method
	
	/*-------------------------------------------------------------------------------------------------
	This is for user signin
	All the error checking is done here. If email and password is valid redirect to user panel
	-------------------------------------------------------------------------------------------------*/
	public function signin(){
		# If $_POST['submit'] is set, form has been submitted
			if( isset($_POST['submit'])){
			# Get the submitted values
				$error_num=0;
				$email = trim($_POST['email']);
				$password = trim($_POST['password']);
			
			# If Email Field is empty, set error flag
				if( empty($email) ){
					$_SESSION['error_email'] = "Email field is empty";
					$error_num++;
				}
			
			# If Password field is empty, set error flag
				if( empty($password) ){
					$_SESSION['error_password'] = "Password field is empty";
					$error_num++;
				}
			
			# If no error, Check if the email and password match
				if( $error_num == 0 ){
					$email = $this->db->sanitize( $email );
					$password = $this->db->sanitize( $password );
					$password = sha1( $password . $email );
					$sql = "SELECT * FROM user_info WHERE email = '$email' AND password='$password'";
					$result = $this->db->select_row($sql);
				
				# Email and password match. Set session and redirect to User panel
					if( $result ){
						$_SESSION['is_user_logged_in'] = true;
						$_SESSION['user_id'] = $result['id'];
						$_SESSION['first_name'] = $result['first_name'];
						$_SESSION['last_name'] = $result['last_name'];
						header("Location:/user");
						die();
					}else{
					# Email and password doesn't match. Set error flag
						$_SESSION['error_login'] = "Invalid username or Password";
					}
				}
				if( (!isset($_SESSION['error_email'])) && (!isset($_SESSION['error_login'])) ) {
					$_SESSION['email'] = $email;
				}
			}
		
		# If there is error or form not submitted, Redirect to homepage
			header("Location:/");
			die();
	} # End of signin method
	
} # End of class
