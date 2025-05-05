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
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$this->load->view('admin/dashboard');
		}
	}
	public function blogs()
	{
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$this->load->view('admin/blogs');
		}
	}
	public function save_blogs() {
		$this->load->library('form_validation');
	
		$this->form_validation->set_rules('title', 'Title', 'required|trim');
		$this->form_validation->set_rules('slug', 'Slug', 'required|trim|is_unique[tbl_blogs.slug]');
		$this->form_validation->set_rules('content', 'Content', 'required');	
		$this->form_validation->set_rules('featured_image', 'Featured Image', 'callback_file_check');
		$this->form_validation->set_message('file_check', 'Please select a valid image file.');
		
		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Validation failed.',
				'errors' => $this->form_validation->error_array()
			]);
			return;
		}
		// Handle File Upload
		$featured_image = null;
		if (!empty($_FILES['featured_image']['name'])) {
			$config['upload_path'] = './uploads/blogs/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['file_name'] = time() . '_' . $_FILES['featured_image']['name'];
			$config['overwrite'] = false;
	
			$this->load->library('upload', $config);
	
			if (!$this->upload->do_upload('featured_image')) {
				echo json_encode([
					'status' => 'error',
					'message' => 'Image upload failed.',
					'upload_error' => strip_tags($this->upload->display_errors())
				]);
				return;
			} else {
				$featured_image = $this->upload->data('file_name');
			}
		}
	
		$data = [
			'title' => $this->input->post('title'),
			'slug' => $this->input->post('slug'),
			'content' => $this->input->post('content'),
			'featured_image' => $featured_image,
		];
	
		$insert = $this->model->insertData('tbl_blogs', $data);
	
		if ($insert) {
			echo json_encode([
				'status' => 'success',
				'message' => 'Blog saved successfully.'
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Failed to save blog. Please try again.'
			]);
		}
	}
	public function file_check($str) {
		if (empty($_FILES['featured_image']['name'])) {
			$this->form_validation->set_message('file_check', 'Please select a file to upload.');
			return FALSE;
		} else {
			$allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
			$file_type = pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION);
	
			if (!in_array($file_type, $allowed_types)) {
				$this->form_validation->set_message('file_check', 'Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.');
				return FALSE;
			}
		}
		return TRUE;
	}
	public function blogs_data_on_datatable(){
		$response['data'] = $this->model->selectWhereData('tbl_blogs', array('is_delete' => '1'), '*', false,array('id'=>'desc'));
		$response['status'] = 'success';
		echo json_encode($response);
	} 
	public function blogs_data_on_id(){
		$id = $this->input->post('id');
		$response['data'] = $this->model->selectWhereData('tbl_blogs', array('id' => $id), '*', true);
		$response['status'] = 'success';
		echo json_encode($response);
	}
	public function update_blog() {
		$response = ['status' => 'error'];
	
		$this->load->library('form_validation');
	
		// Set validation rules
		$this->form_validation->set_rules('edit_title', 'Title', 'required');
		$this->form_validation->set_rules('edit_slug', 'Slug', 'required');
		$this->form_validation->set_rules('edit_content', 'Content', 'required');
		$this->form_validation->set_rules('edit_status', 'Status', 'required');
	
		if ($this->form_validation->run() == FALSE) {
			$response['errors'] = [
				'title' => form_error('edit_title'),
				'slug' => form_error('edit_slug'),
				'content' => form_error('edit_content'),
				'status' => form_error('edit_status'),
			];
			echo json_encode($response);
			return;
		}
	
		$id = $this->input->post('edit_blog_id');
		$title = $this->input->post('edit_title');
		$slug = $this->input->post('edit_slug');
		$content = $this->input->post('edit_content');
		$status = $this->input->post('edit_status');
	
		// Default to no image change
		$featured_image = '';
	
		// Handle image upload
		if (!empty($_FILES['edit_featured_image']['name'])) {
			$config['upload_path'] = './uploads/blogs/';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['file_name'] = time() . '_' . $_FILES['edit_featured_image']['name'];
			$config['overwrite'] = FALSE;
	
			$this->load->library('upload', $config);
	
			if ($this->upload->do_upload('edit_featured_image')) {
				$upload_data = $this->upload->data();
				$featured_image = $upload_data['file_name'];
			} else {
				$response['errors'] = ['featured_image' => $this->upload->display_errors()];
				echo json_encode($response);
				return;
			}
		}
	// Prepare data for update
		$data = [
			'title' => $title,
			'slug' => $slug,
			'content' => $content,
			'status' => $status,
		];
	
		if (!empty($featured_image)) {
			$data['featured_image'] = $featured_image;
		}
	
		// Update the blog post
		$updated = $this->model->updateData('tbl_blogs', $data, ['id' => $id]);
	
		if ($updated) {
			$response['status'] = 'success';
			$response['message'] = 'Blog updated successfully.';
		} else {
			$response['message'] = 'No changes were made or update failed.';
		}
	
		echo json_encode($response);
	}
	public function delete_blog()
	{
		$id = $this->input->post('id');
		if (!$id) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid Blog ID.']);
			return;
		}
		// Update blog status to "deleted" or "inactive"
		$data = [
			'is_delete' => '0', // Assuming 0 means deleted
		];
		$updated = $this->model->updateData('tbl_blogs', $data, ['id' => $id]);

		if ($updated) {
			echo json_encode(['status' => 'success', 'message' => 'Blog soft deleted successfully.']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to soft delete blog.']);
		}
	}

	
	public function export_member_data()
	{
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$this->load->view('admin/export_member_data');
		}
	}
	public function export_member_data_on_datatable(){
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$response['data'] = $this->model->selectWhereData('tbl_users', array('is_delete' => '1'), '*', false,array('id'=>'desc'));
			$response['status'] = 'success';
			echo json_encode($response);
		}
	}
	public function export_member_data_on_id(){
		$id = $this->input->post('id');
		$response['data'] = $this->model->selectWhereData('tbl_users', array('id' => $id), '*', true);
		$response['status'] = 'success';
		echo json_encode($response);
	}
	public function export_enquires_data()
	{
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$this->load->view('admin/export_enquires_data');
		}
	}
	public function announcement()
	{
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$this->load->view('admin/announcement');
		}
	}
	public function membership_category()
	{
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$this->load->view('admin/membership_category');
		}
	}
	public function save_category() {
		$this->load->library('form_validation');
	
		// Updated validation rules
		$this->form_validation->set_rules('category_name', 'Category Name', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('description', 'Description', 'required|trim|max_length[1000]');
	
		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Validation failed.',
				'errors' => $this->form_validation->error_array()
			]);
			return;
		}
	
		// Sanitize input values
		$category_name = trim($this->input->post('category_name'));
		$description = trim($this->input->post('description'));
	
		// Check for existing category name (case-insensitive)
		$existing_category = $this->model->selectWhereData(
			'tbl_membership_categories',
			['LOWER(category_name)' => strtolower($category_name)],
			'*',
			true
		);	
		if ($existing_category) {
			echo json_encode([
				'status' => 'error',
				'errors' => [
					'category_name' => 'Category name already exists.'
				]
			]);
			return;
		}
		// Prepare data to insert
		$data = [
			'category_name' => $category_name,
			'description' => $description
		];
	
		// Insert data
		$insert = $this->model->insertData('tbl_membership_categories', $data);
	
		if ($insert) {
			echo json_encode([
				'status' => 'success',
				'message' => 'Category saved successfully.'
			]);
		} else {
			log_message('error', 'Category insert failed: ' . json_encode($data));
			echo json_encode([
				'status' => 'error',
				'message' => 'Failed to save category. Please try again.'
			]);
		}
	}
	
	public function category_data_on_datatable(){
		$response['data'] = $this->model->selectWhereData('tbl_membership_categories', array('is_delete' => '1'), '*', false,array('id'=>'desc'));
		$response['status'] = 'success';
		echo json_encode($response);
	} 
	public function category_data_on_id(){
		$id = $this->input->post('id');
		$response['data'] = $this->model->selectWhereData('tbl_membership_categories', array('id' => $id), '*', true);
		$response['status'] = 'success';
		echo json_encode($response);
	}
	public function update_category() {
		$response = ['status' => 'error'];
		$this->load->library('form_validation');
	
		// Validation rules
		$this->form_validation->set_rules('edit_category_name', 'Category Name', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('edit_description', 'Description', 'required|trim|max_length[1000]');
	
		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Validation failed.',
				'errors' => $this->form_validation->error_array()
			]);
			return;
		}
	
	
		$id = $this->input->post('edit_category_id');
		$category_name = trim($this->input->post('edit_category_name'));
		$description = trim($this->input->post('edit_description'));
	
		// Check for duplicate name (excluding the current ID)
		$existing_category = $this->model->selectWhereData(
			'tbl_membership_categories',
			['category_name' => $category_name, 'id !=' => $id],
			'*',
			true
		);
	
		if ($existing_category) {
			$response['errors'] = [
				'edit_category_name' => 'Category name already exists.'
			];
			echo json_encode($response);
			return;
		}
	
		// Prepare update
		$data = [
			'category_name' => $category_name,
			'description' => $description
		];
	
		$updated = $this->model->updateData('tbl_membership_categories', $data, ['id' => $id]);
	
		if ($updated) {
			$response['status'] = 'success';
			$response['message'] = 'Category updated successfully.';
		} else {
			$response['message'] = 'No changes were made or update failed.';
		}
	
		echo json_encode($response);
	}
	
	public function delete_category()
	{
		$id = $this->input->post('id');
		if (!$id) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid Blog ID.']);
			return;
		}
		// Update blog status to "deleted" or "inactive"
		$data = [
			'is_delete' => '0', // Assuming 0 means deleted
		];
		$updated = $this->model->updateData('tbl_membership_categories', $data, ['id' => $id]);

		if ($updated) {
			echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully.']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to delete category.']);
		}
	}
	public function membership_types()
	{
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$response['categories'] = $this->model->selectWhereData('tbl_membership_categories', array('is_delete' => '1'), '*', false,array('id'=>'desc'));
			$response['currencies'] = $this->model->selectWhereData('tbl_currency', array(), '*', false,array('id'=>'desc'));
			$this->load->view('admin/membership_types',$response);
		}
	}
	public function save_membership_type() {
		$this->load->library('form_validation');
	
		$this->form_validation->set_rules('category_name', 'Category', 'required');
		$this->form_validation->set_rules('type_name', 'Type Name', 'required|trim|max_length[100]');
		$this->form_validation->set_rules('currency', 'Currency', 'required|trim|max_length[10]');
		$this->form_validation->set_rules('price', 'Price', 'required|numeric');
		$this->form_validation->set_rules('short_description', 'Short Description', 'required|trim|max_length[255]');
		$this->form_validation->set_rules('full_description', 'Full Description', 'required|trim');
	
		if ($this->form_validation->run() == FALSE) {
			echo json_encode([
				'status' => 'error',
				'errors' => $this->form_validation->error_array()
			]);
			return;
		}
	
		$category_id = $this->input->post('category_name');
		$type_name = trim($this->input->post('type_name'));
	
		// Check for existing type under same category
		$exists = $this->model->selectWhereData(
			'tbl_membership_types',
			['fk_category_id' => $category_id, 'LOWER(type_name)' => strtolower($type_name)],
			'*',
			true
		);
	
		if ($exists) {
			echo json_encode([
				'status' => 'error',
				'errors' => ['type_name' => 'This membership type already exists under the selected category.']
			]);
			return;
		}
	
		$data = [
			'fk_category_id' => $category_id,
			'type_name' => $type_name,
			'currency' => $this->input->post('currency'),
			'price' => $this->input->post('price'),
			'short_description' => $this->input->post('short_description'),
			'full_description' => $this->input->post('full_description'),
		];
	
		$insert = $this->model->insertData('tbl_membership_types', $data);
	
		if ($insert) {
			echo json_encode([
				'status' => 'success',
				'message' => 'Membership type saved successfully.'
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Failed to save membership type. Please try again.'
			]);
		}
	}
	public function membership_type_data_on_datatable(){
		$this->load->model('Admin_model');
		$response['data'] = $this->Admin_model->membership_type_data_on_datatable();
		$response['status'] = 'success';
		echo json_encode($response);
	} 
	public function membership_type_data_on_id(){
		$this->load->model('Admin_model');
		$id = $this->input->post('id');
		$response['data'] = $this->Admin_model->membership_type_data_on_id($id);
		$response['status'] = 'success';
		echo json_encode($response);
	}
	public function update_membership_type()
	{
		$this->load->library('form_validation');
		$response = ['status' => false, 'errors' => [], 'message' => ''];

		// Gather the posted data
		$id = $this->input->post('edit_membership_type_id');
		$category_id = $this->input->post('edit_category_name');
		$type_name = trim($this->input->post('edit_type_name'));
		$currency_id = $this->input->post('edit_currency');
		$price = trim($this->input->post('edit_price'));
		$short_desc = trim($this->input->post('edit_short_description'));
		$full_desc = trim($this->input->post('edit_full_description'));

		// === Form Validation ===
		$this->form_validation->set_rules('edit_category_name', 'Category', 'required');
		$this->form_validation->set_rules('edit_type_name', 'Type Name', 'required');
		$this->form_validation->set_rules('edit_currency', 'Currency', 'required');
		$this->form_validation->set_rules('edit_price', 'Price', 'required|numeric');
		$this->form_validation->set_rules('edit_short_description', 'Short Description', 'required');
		$this->form_validation->set_rules('edit_full_description', 'Full Description', 'required');

		// Run the form validation
		if ($this->form_validation->run() == FALSE) {
			// Return validation errors if validation fails
			$response['status'] = 'error'; // Explicitly setting this here
			$response['errors'] = [
				'edit_category_name' => form_error('edit_category_name'),
				'edit_type_name' => form_error('edit_type_name'),
				'edit_currency' => form_error('edit_currency'),
				'edit_price' => form_error('edit_price'),
				'edit_short_description' => form_error('edit_short_description'),
				'edit_full_description' => form_error('edit_full_description'),
			];
		} else {
			// === Duplicate Check ===
			$duplicate = $this->model->selectWhereData(
				'tbl_membership_types',
				['fk_category_id' => $category_id, 'LOWER(type_name)' => strtolower($type_name), 'id !=' => $id],
				'*',
				true
			);

			// Handle duplicate case
			if ($duplicate) {
				$response['status'] = 'error';
				$response['errors'] = ['edit_type_name' => 'This type name already exists for the selected category.'];
			} else {
				// === Perform Update ===
				$data = [
					'fk_category_id' => $category_id,
					'type_name' => $type_name,
					'fk_currency_id' => $currency_id,
					'price' => $price,
					'short_description' => $short_desc,
					'full_description' => $full_desc,
				];

				// Update record in the database
				$updateSuccess = $this->model->updateData('tbl_membership_types', $data, ['id' => $id]);

				if ($updateSuccess) {
					$response['status'] = 'success';
					$response['message'] = 'Membership Type updated successfully.';
				} else {
					$response['status'] = 'error';
					$response['message'] = 'Failed to update the Membership Type.';
				}
			}
		}

		// Return the response as JSON
		echo json_encode($response);
	}
	public function delete_membership_type()
	{
		$id = $this->input->post('id');
		if (!$id) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid Blog ID.']);
			return;
		}
		// Update blog status to "deleted" or "inactive"
		$data = [
			'is_delete' => '0', // Assuming 0 means deleted
		];
		$updated = $this->model->updateData('tbl_membership_types', $data, ['id' => $id]);

		if ($updated) {
			echo json_encode(['status' => 'success', 'message' => 'Membership Type deleted successfully.']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Failed to delete category.']);
		}
	}
	public function team_member()
	{
		$admin_session = $this->session->userdata('admin_session');
		if (empty($admin_session)) {
			redirect('common');
		}else{
			$this->load->view('admin/team_member');
		}
	}
}