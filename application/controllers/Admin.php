<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
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

		$data['title'] = "Dashboard";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('templates/footer');
	}

	public function role()
	{
		// buat session data user
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		// ambil data dari database user_role
		$data['role'] = $this->db->get('user_role')->result_array();
		$data['title'] = "Role";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/role', $data);
		$this->load->view('templates/footer');
	}

	public function roleAccess($role_id)
	{
		// buat session data user
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		// ambil data dari database user_role yang sesuai dengan $role_id
		$data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
		// hilangkan member admin 
		$this->db->where('id !=', 1);
		// dapatkan semua data menu dari tabel user_menu
		$data['menu'] = $this->db->get('user_menu')->result_array();
		$data['title'] = "Role";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/role-access', $data);
		$this->load->view('templates/footer');
	}

	public function changeAccess()
	{
		// ambil data yang dikirm dari ajak lewat metode post
		$menu_id = $this->input->post('menuId');
		$role_id = $this->input->post('roleId');
		// munculkan isi data dari tabel user access menu dalam bentuk object
		$data = [
			'role_id' => $role_id,
			'menu_id' => $menu_id
		];
		// query data diatas
		$result = $this->db->get_where('user_access_menu', $data);
		// buat kondisi kalau data ada delete kalau data tidak ada tambahkan
		if ($result->num_rows() < 1) {
			// kalau data tidak ada insert ke tabel user_access_menu
			$this->db->insert('user_access_menu', $data);
		} else {
			// kalau data ada delete dari tabel user_access_menu
			$this->db->delete('user_access_menu', $data);
		}
		// berikan alert kalau data berubah
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Access Changed! </div>');
	}
}
