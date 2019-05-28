<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	// Kondisi Untuk Tendang User Yang Mencoba Masuk Secara Paksa Dan Akses Sidebar Sesuai Role Id.
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
	}

	public function index()
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

		$data['title'] = "My Profile";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('user/index', $data);
		$this->load->view('templates/footer');
	}

	public function edit()
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

		// buat rule form validation edit
		$this->form_validation->set_rules('name', 'Full Name', 'required|trim');

		if ($this->form_validation->run() == false) {
			$data['title'] = "Edit Profile";
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('user/edit', $data);
			$this->load->view('templates/footer');
		} else {
			// ambil data dari input
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			// cek jika ada gambar yang akan diubah
			$upload_image = $_FILES['image']['name'];
			if ($upload_image) {
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']     = '2048';
				$config['upload_path'] = './assets/img/profile/';
				$this->load->library('upload', $config);
				// berikan kondisi
				if ($this->upload->do_upload('image')) {
					// ambil data user image
					$old_image = $data['user']['image'];
					// berikan kondisi jika bukan gambar default maka hapus gambar setelah edit gambar
					if ($old_image != 'default.png') {
						// code untuk melacak lokasi image dan menghapusnya
						unlink(FCPATH . 'assets/img/profile/' . $old_image);
					}
					//berikan nama kepada file baru
					$new_image = $this->upload->data('file_name');
					// tambah set baru bila ada image baru
					$this->db->set('image', $new_image);
				} else {
					echo $this->upload->display_errors();
				}
			}
			// query edit profile
			$this->db->set('name', $name);
			$this->db->where('email', $email);
			$this->db->update('user');
			// tampilkan alert bila berhasil mengedit data user
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Your profile has been updated! </div>');
			redirect('user');
		}
	}

	public function changePassword()
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		// tentukan rule validasi untuk inputan change password
		$this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
		$this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[3]|matches[new_password2]');
		$this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[3]|matches[new_password1]');
		// validasi inputan untuk change password
		if ($this->form_validation->run() == false) {
			$data['title'] = "Change Password";
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('user/changepassword', $data);
			$this->load->view('templates/footer');
		} else {
			// ambil data current passwor dari inputan change password
			$current_password = $this->input->post('current_password');
			// ambil data new password dari inputan change password
			$new_password = $this->input->post('new_password1');
			// cek apakan pasword yang dimasukan user sama dengan password yang ada di database
			if (!password_verify($current_password, $data['user']['password'])) {
				// tampilkan alert bila current password salah
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				Wrong current password! </div>');
				redirect('user/changepassword');
			} else {
				// cek apakah current password sama dengan new password
				if ($current_password == $new_password) {
					// tampilkan alert bila data current password dan new password sama 
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current password! </div>');
					redirect('user/changepassword');
				} else {
					// acak password 
					$password_hash = password_hash($new_password, PASSWORD_DEFAULT);
					// query update
					$this->db->set('password', $password_hash);
					$this->db->where('email', $this->session->userdata('email'));
					$this->db->update('user');
					// tampilkan alert bila current password salah
					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
					Password changed! </div>');
					redirect('user/changepassword');
				}
			}
		}
	}
}
