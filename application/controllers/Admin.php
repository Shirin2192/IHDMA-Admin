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
}