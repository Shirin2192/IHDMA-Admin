<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->view('admin/login');
	}
    public function register()
    {
        $this->load->view('admin/register');
    }
	public function registration(){
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$role_id = $this->input->post('fk_role_id');
	
		// Form Validation Rules
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[tbl_users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

	
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('common/login');
		} else {
			$data = [
				'name' => $name,
				'email' => $email,
				'password' => decy_ency('encrypt',$password), 
			];
	
			$response = $this->model->insertData('tbl_users', $data);
	
			if ($response) {
				echo json_encode(["status" => "success", "message" => "Registration successful!"]);
			} else {
				echo json_encode(["status" => "error", "message" => "Registration failed. Try again."]);
			}
		}
	}

	public function login_process() {
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			$response = [
				'status' => 'error',
				'email_error' => form_error('email'),
				'password_error' => form_error('password')
			];
		} else {
			$email = $this->input->post('email');
			$password = $this->input->post('password');
	
			// Fetch user data
			$user = $this->model->selectWhereData("tbl_users", array("email" => $email));
	
			if ($user) {
				$stored_password = $user['password'];
				if ($stored_password == decy_ency('encrypt', $password)) { 
	
					// Set fixed session and redirect
					$session_data = [
						'id'   => $user['id'],
						'name'      => $user['name'],
						'email'     => $user['email'],
						'session_type' => 'admin_session'
					];
	
					$this->session->set_userdata('admin_session', $session_data);
	
					$response = ['status' => 'success', 'redirect' => base_url('admin')];
				} else {
					$response = ['status' => 'error', 'login_error' => 'Invalid Email or Password'];
				}
			} else {
				$response = ['status' => 'error', 'login_error' => 'Invalid Email or Password'];
			}
		}
	
		echo json_encode($response);
	}
	
	
	
	public function logout() {
		// Destroy session data
		$this->session->sess_destroy(); // Completely destroy session
	
		// Redirect to login page
		redirect(base_url('common/index'));
	}
}
