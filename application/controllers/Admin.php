<?php
ob_start(); // Start output buffering
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
    }
	public function dashboard()
	{
		$this->load->view('admin/dashboard');
	}
	public function blogs()
	{
		$this->load->view('admin/blogs');
	}
	public function export_member_data()
	{
		$this->load->view('admin/export_member_data');
	}
	public function export_enquires_data()
	{
		$this->load->view('admin/export_enquires_data');
	}
	public function announcement()
	{
		$this->load->view('admin/announcement');
	}
}