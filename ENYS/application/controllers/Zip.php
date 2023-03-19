<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zip extends CI_Controller {

	public function __construct() {   
		parent::__construct();   
		$this->load->library('zip');   
	 }  

     function download() {
         $this->zip->read_dir('./uploads/');
         $this->zip->archive('./uploads/'.'images.zip');
         $this->zip->download('images.zip');
     }

}