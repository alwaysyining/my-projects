<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends CI_Controller {

	public function __construct() {   
		parent::__construct();   
		$this->load->helper('url');   
	 }  

    public function index()
    {
		$this->load->view('template/header'); 
    	if (!$this->session->userdata('logged_in'))//check if user already login
		{	
			if (get_cookie('remember')) { // check if user activate the "remember me" feature  
				$username = get_cookie('username'); //get the username from cookie
				$password = get_cookie('password'); //get the username from cookie
				if ( $this->user_model->login($username, $password) )//check username and password correct
				{
					$user_data = array('username' => $username,'logged_in' => true );
					$this->session->set_userdata($user_data); //set user status to login in session
					$this->load->view('file',array('error' => ' ')); //if user already logined show upload page
					$this->load->view('file_dragdrop',array('error' => ' ')); //if user already logined show upload page
				}
			}else{
				redirect('login'); //if user already logined direct user to home page
			}
		}else{
			$this->load->view('file',array('error' => ' ')); //if user already logined show login page
			$this->load->view('file_dragdrop',array('error' => ' ')); //if user already logined show upload page
		}
		$this->load->view('template/footer');
    }
    public function do_upload() {
		$data = array();
		$errorUploadType = $statusMsg = ''; 
		$filesCount = count($_FILES['userfiles']['name']);
		$this->load->model('file_model');

		if(!empty($_FILES['userfiles']['name']) && count(array_filter($_FILES['userfiles']['name'])) > 0){
			for($i = 0; $i < $filesCount; $i++){
				// Define new $_FILES array - $_FILES['file']
				$_FILES['userfile']['name']     = $_FILES['userfiles']['name'][$i]; 
				$_FILES['userfile']['type']     = $_FILES['userfiles']['type'][$i]; 
				$_FILES['userfile']['tmp_name'] = $_FILES['userfiles']['tmp_name'][$i]; 
				$_FILES['userfile']['error']     = $_FILES['userfiles']['error'][$i]; 
				$_FILES['userfile']['size']     = $_FILES['userfiles']['size'][$i]; 

				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = 'jpg|mp4|mkv';
				$config['max_size'] = 100000;
				$config['max_width'] = 6000;
				$config['max_height'] = 6000;

				// Load and initialize upload library 
				$this->load->library('upload', $config); 
				$this->upload->initialize($config);
				
				// Upload file to server 
				if (! $this->upload->do_upload('userfile')) { 
					$this->load->view('template/header');
					$data = array('error' => $this->upload->display_errors());
					$this->load->view('file', $data);
					$this->load->view('file_dragdrop');
					$this->load->view('template/footer');
				} else {  
					// Uploaded file data 
					$this->file_model->upload($this->upload->data('file_name'), $this->upload->data('full_path'),$this->session->userdata('username'));
				}
			}
			$this->load->view('template/header');
			$this->load->view('file', array('error' => 'File(s) upload success. <br/>'));
			$this->load->view('file_dragdrop');
			$this->load->view('template/footer');
		} else {
			$this->load->view('template/header');
			$this->load->view('file', array('error' => '0 file selected. <br/>'));
			$this->load->view('file_dragdrop');
			$this->load->view('template/footer');
		}
	}
}

