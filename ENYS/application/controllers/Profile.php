<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
		$this->load->helper('form');
        $this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
    }
	
	/* public function index()
	{
		$this->load->view('template/header.php');
		$this->load->view('my_profile.php');
		$this->load->view('template/footer.php');
	} */

	public function index()
    {
		$data['error']= "";
		$user = $this->session->userdata('username');
		$data['user'] = $this->User_model->getuser($user);
		/*
		$query = $this->User_model->getuser('$user'); // Use the getuser() function in User_model
        $data = array ('data' => $query);
		*/

		$this->load->view('template/header'); 
    	
		if (!$this->session->userdata('logged_in'))//check if user already login
		{	
			if (get_cookie('remember')) { // check if user activate the "remember me" feature  
				$username = get_cookie('username'); //get the username from cookie
				$password = get_cookie('password'); //get the username from cookie
				if ($this->user_model->login($username, $password)) {//check username and password correct
					$user_data = array('username' => $username, 'logged_in' => true);
					$this->session->set_userdata($user_data); //set user status to login in session
					$this->load->view('my_profile', $data); //if user already logined show main page
				}
			} else {
				redirect('login');	//if username password incorrect, show error msg and ask user to login
			}
		} 

		$this->load->view('my_profile', $data);
		$this->load->view('template/footer');
    }
	
}