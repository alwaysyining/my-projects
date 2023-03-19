<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discussion extends CI_Controller {

	public function index()
	{
		$this->load->view('template/header.php');
		$this->load->view('discussion_boards.php');
		$this->load->view('template/footer.php');
	}
}