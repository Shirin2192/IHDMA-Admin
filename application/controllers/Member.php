<?php
ob_start(); // Start output buffering
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller {
	public function __construct()
	{
        parent::__construct();		
		$this->load->library('form_validation');
    }
	public function register_user() {
		$name         = $this->input->post('name');
		$user_name    = $this->input->post('user_name');
		$email        = $this->input->post('email');
		$password     = $this->input->post('password');
		$mobile       = $this->input->post('mobile');
		$address1     = $this->input->post('address1');
		$address2     = $this->input->post('address2');
		$city         = $this->input->post('city');
		$state        = $this->input->post('state');
		$postal_code  = $this->input->post('postal_code');
		$country      = $this->input->post('country');
		$fax          = $this->input->post('fax');
		$dob          = $this->input->post('dob');
		$terms_accepted = $this->input->post('terms_accepted');
	
		// Check for existing email or username
		$exist_email = $this->model->selectWhereData('tbl_users', ['email' => $email, 'user_name' => $user_name, 'is_delete' => '0']);
		if ($exist_email) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Email or Username already exists.'
			]);
			return;
		}
		$exist_contact = $this->model->selectWhereData('tbl_users', ['mobile' => $mobile, 'is_delete' => '0']);
		if ($exist_contact) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Mobile number already exists.'
			]);
			return;
		}	
		// Hash the password (strongly recommended)
		$hashed_password = decy_ency('encrypt', $password);
	
		// Insert new user
		$insertData = [
			'name'           => $name,
			'user_name'      => $user_name,
			'email'          => $email,
			'password'       => $hashed_password,
			'mobile'         => $mobile,
			'address1'       => $address1,
			'address2'       => $address2,
			'city'           => $city,
			'state'          => $state,
			'postal_code'    => $postal_code,
			'country'        => $country,
			'fax'            => $fax,
			'dob'            => $dob,
			'terms_accepted' => $terms_accepted
		];
	
		$insert = $this->model->insertData('tbl_users', $insertData);
		if ($insert) {
			echo json_encode([
				'status' => 'success',
				'message' => 'User registered successfully.'
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'User registration failed.'
			]);
		}
	}
	public function login_user() {
		$email    = $this->input->post('email');
		$password = $this->input->post('password');
	
		// Validate input
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'email_error' => form_error('email'),
				'password_error' => form_error('password')
			]);
			return;
		}
		// Check if user exists
		$user = $this->model->selectWhereData('tbl_users', ['email' => $email, 'is_delete' => '1']);
	
		if ($user) {
			if ($user['password'] === decy_ency('encrypt', $password)) {
				echo json_encode([
					'status' => 'success',
					'message' => 'Login successful.',
					'user_data' => $user
				]);
			} else {
				echo json_encode([
					'status' => 'error',
					'message' => 'Invalid password.'
				]);
			}
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'User not found.'
			]);
		}	
	}
	public function logout_user() {
		$this->session->unset_userdata('user_data');
		$this->session->sess_destroy();
		redirect('login');
	}
	public function forgot_password() {
		$email = $this->input->post('email');
	
		// Validate input
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'email_error' => form_error('email')
			]);
			return;
		}
	
		// Check if user exists
		$user = $this->model->selectWhereData('tbl_users', ['email' => $email, 'is_delete' => '1']);
	
		if ($user) {
			echo json_encode([
				'status' => 'success',
				'message' => 'Password reset link sent to your email.'
			]);
			// Here you would typically send an email with a password reset link.
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'User not found.'
			]);
		}
	}

	public function get_membership_planes(){
		$this->load->model('Member_model');
		$response['data'] = $this->Member_model->get_membership_planes();
		$response['status'] = 'success';
		$response['message'] = 'Membership plans fetched successfully.';
		echo json_encode($response);
	}
    public function get_membership_plans_by_id($id){
		$this->load->model('Member_model');
		$id = $this->input->post('id');
		$response['data'] = $this->Member_model->get_membership_plans_by_id($id);
		$response['status'] = 'success';
		$response['message'] = 'Membership plan fetched successfully.';
		echo json_encode($response);
	}
	public function team_members(){
		$response['data'] = $this->model->selectWhereData('tbl_team_members', ['is_delete' => '1'],'*',false);
		$response['status'] = 'success';
		$response['message'] = 'Team members fetched successfully.';
		echo json_encode($response);
	}
	public function get_team_member_by_id($id){
		$id = $this->input->post('id');
		$response['data'] = $this->model->selectWhereData('tbl_team_members', ['is_delete' => '1','id' => $id],'*',true);
		$response['status'] = 'success';
		$response['message'] = 'Team member fetched successfully.';
		echo json_encode($response);
	}

}