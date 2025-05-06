<?php
ob_start();
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *"); // or use a specific domain instead of '*'
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class Common extends CI_Controller {

	public function __construct() {
		parent::__construct();
		
		
	}

	public function index() {
		$this->load->view('admin/login');
	}

	public function register() {
		$this->load->view('admin/register');
	}

	public function registration() {
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$mobile = $this->input->post('mobile');
		$membership_type = $this->input->post('membership_type');

		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[tbl_users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required');
		//$this->form_validation->set_rules('membership_type', 'Membership Type', 'in_list[nursing,doctor,corporate,none]');

		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'name' => form_error('name'),
				'email' => form_error('email'),
				'password' => form_error('password'),
				'mobile' => form_error('mobile'),
			]);
			return;
		}
		$exist_membership = $this->model->selectWhereData('tbl_users', ['email' => $email]);
		if ($exist_membership) {
			echo json_encode(["status" => "error", "message" => "Email already exists."]);
			return;
		}
		$exist_mobile = $this->model->selectWhereData('tbl_users', ['mobile' => $mobile]);
		if ($exist_mobile) {
			echo json_encode(["status" => "error", "message" => "Mobile number already exists."]);
			return;
		}

		$data = [
			'name' => $name,
			'email' => $email,
			'password' => decy_ency('encrypt', $password),
			'user_type' => 'admin',
			'mobile' => $mobile,
			'membership_type' => $membership_type ?? 'none',
		];
		$response = $this->model->insertData('tbl_users', $data);

		if ($response) {
			echo json_encode(["status" => "success", "message" => "Registration successful!"]);
		} else {
			$error = $this->db->error();
			log_message('error', 'Registration DB Error: ' . print_r($error, true));
			echo json_encode(["status" => "error", "message" => "Registration failed. Try again."]);
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

			$user = $this->model->selectWhereData("tbl_users", ["email" => $email]);

			if ($user) {
				$stored_password = $user['password'];

				// Decrypt and compare passwords
				if (decy_ency('decrypt', $stored_password) === $password) {
					$session_data = [
						'id' => $user['id'],
						'name' => $user['name'],
						'email' => $user['email'],
						'session_type' => 'admin_session'
					];

					$this->session->set_userdata('admin_session', $session_data);
					$response = ['status' => 'success', 'redirect' => base_url('admin/dashboard')];
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
		$this->session->sess_destroy();
		redirect(base_url('common/index'));
	}
}
