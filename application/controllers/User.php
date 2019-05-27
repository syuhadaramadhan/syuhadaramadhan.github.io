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
					//berikan nama kepada file baru
					$new_image = $this->upload->data('file_name');
					// set bila ada image baru
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
}
