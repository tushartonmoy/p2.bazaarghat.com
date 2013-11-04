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
	Accessed via http://localhost/index/index/
	-------------------------------------------------------------------------------------------------*/
	public function index() {
		
		# People will redirect to the stream page
			header("Location:/user/stream/");
			die();

	} # End of method

} # End of class