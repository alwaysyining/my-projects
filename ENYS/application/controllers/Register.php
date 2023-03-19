<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	// Constructor
	public function __construct(){
		parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->helper('captcha');
        $this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('User_model');
	}

	public function index()
	{
		$data['error']= "";
		$this->load->view('template/header');
		$this->load->view('register', $data);
		$this->load->view('template/footer');
	}

	// Register a new user
    public function check_register() {

        $unique_username = $this->User_model->check_username($this->input->post('username'));
		$unique_email = $this->User_model->check_email($this->input->post('email'));
		$password = $this->input->post('password');
		$confirm_password = $this->input->post('confirm_password');
		$phone = $this->input->post('phone');
		$birthday = $this->input->post('birthday');

        if (!$unique_username) {
            $this->load->view('template/header');
            $this->load->view('register', array('error' => 'The username has already been taken. <br/>'));
            $this->load->view('template/footer');
        } else {
			$username = $this->input->post('username');
			if ($password !== $confirm_password) {
				$this->load->view('template/header');
				$this->load->view('register', array('error' => 'The password is different from confirm password. <br/>'));
				$this->load->view('template/footer');
			} else {
				if (!$unique_email) {
					$this->load->view('template/header');
					$this->load->view('register', array('error' => 'The Email has already been taken. <br/>'));
					$this->load->view('template/footer');
				} else {
					$email = $this->input->post('email');
				}
			}
		}
			
        // Check password strength and validation
        if (strlen($password) < 6 || !preg_match("#[0-9]+#", $password)  || !preg_match("#[a-z]+#", $password) || !preg_match("#[A-Z]+#", $password)) {
            $this->load->view('template/header');
            $this->load->view('register', array('error' => 'The passworld does not meet the requirements. <br/>'));
            $this->load->view('template/footer');
        } else {
                 
            $sent = $this->send_email($email);

            // Check if mail is sent successfully and insert user into database
            if ($sent) {
                $verified = 'YES';
                $this->User_model->insert_user( $username, $password, $email, $phone, $birthday, $verified);
                $this->load->view('template/header');
                $data['error']= "<div class=\"alert alert-success\" role=\"alert\"> Strong password. </div> ";
                $this->load->view('login', $data);
                $this->load->view('template/footer');
            } else {
                $this->load->view('template/header');
                $data['error']= "<div class=\"alert alert-danger\" role=\"alert\"> Email cannot be sent. <br/> </div> ";
                $this->load->view('login', $data);
                $this->load->view('template/footer');
            }
        }
        
    }

	public function send_email($email)
    {
        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'mailhub.eait.uq.edu.au',
            'smtp_port' => 25,
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE ,
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n"
            );
           
        $this->email->initialize($config);
        $this->email->from('s4597031@student.uq.edu.au', 'project');
        $this->email->to($email);
        $this->email->subject('Verification mail');
        $this->email->message('Welcome to ENYS. You have registered successfully. Enjoy yourself!');
        $sent = $this->email->send();
        return $sent;
    }
    
    // Edit profile
    public function edit_profile() {
        $data['error']= "";
        $user = $this->session->userdata('username');
        $data['user'] = $this->User_model->getuser($user);
		if (empty($data['user'])){
			show_404();
		}
        
        $username = $data['user']['username'];
        $password = $data['user']['password'];
        $email = $data['user']['email'];
        $this->load->view('template/header');
        $this->load->view('my_profile_edit', $data);
		$this->load->view('template/footer');

        if (array_key_exists('update_cancel', $_POST)) {
            redirect('login');
        }

        if (array_key_exists('profile_update', $_POST)) {
            $this->User_model->edit_user($username, $password, $email, $this->input->post('phone'), $this->input->post('birthday'));
            redirect('login');
        }

    }




}
