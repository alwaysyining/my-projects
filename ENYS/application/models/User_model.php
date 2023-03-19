<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 //put your code here
 class User_model extends CI_Model{

    public function __construct() {
        parent::__construct();
        $this->load->helper('array');
        $this->load->database();
    }

    // Log in with hash
    public function login($username, $password) {
        // Validate

        // $query = $this->db->get_where('users', array('username' => $username));
        $this->db->select('password');
		$this->db->from('users');
		$this->db->where('username', $username);
        $query = $this->db->get();
        $result = $query->result_array();
        $a = $result[0];
        $pw = element('password', $a);
        
        // echo implode(",",$a);
        // echo $pw;

        if (password_verify($password, $pw)) {
            return true;
        } else {
            return false;
        }
    }

    /*
    // Log in
    public function login($username, $password) {
        // Validate
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $result = $this->db->get('users');

        if($result->num_rows() == 1){
            return true;
        } else {
            return false;
        }
    }
    */

    // Get user profile
    function getuser($username) {
        $data['user'] = $this->db->get_where('users', array('username'=>$username))->row_array();
		if (empty($data['user'])) {
			show_404();
		}

        $query = $this->db->get_where('users', array('username'=>$username));
        return $query->row_array();
    }

    // Register a new user
    public function insert_user($username, $password, $email, $phone, $birthday, $verified) 
    {
        $user = array(
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'email' => $email,
            'phone' => $phone,
            'birthday' => $birthday,
            'verified_status' => $verified
        );
        $query = $this->db->insert('users', $user);
    }

    // Check if username is unique
    public function check_username($username) {
        $this->db->where('username', $username);
        
        $result = $this->db->get('users');

        if($result->num_rows() == 1){
            return false;
        } else {
            return true;
        }
    }

    // Check if email is unique
    public function check_email($email) {
        $this->db->where('email', $email);

        $result = $this->db->get('users');

        if($result->num_rows() == 1){
            return false;
        } else {
            return true;
        }
    }

    // Edit user
    public function edit_user($username, $password, $email, $phone, $birthday) {
        $user = array(
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'phone' => $phone,
            'birthday' => $birthday
        );
        $query = $this->db->where('username', $username)->update('users', $user);
    }

}
?>