<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {

	// Kondisi Untuk Tendang User Yang Mencoba Masuk Secara Paksa Dan Akses Sidebar Sesuai Role Id.
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
	}

	public function index()
	{
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		// Dapatkan Data Dari Tabel User Menu.
		$data['menu'] = $this->db->get('user_menu')->result_array();
		// Berikan Rule Untuk Data Dari Input Modal Add New Menu.
		$this->form_validation->set_rules('menu', 'Menu', 'required');
		// Validasi Data Dari Modal Add New Menu.
		if ($this->form_validation->run() == false)
		{
			$data['title'] = "Menu Management";
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('menu/index', $data);
			$this->load->view('templates/footer');
		} else
		{
			$this->db->insert('user_menu', ['menu' => $this->input->post('menu')]);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			New menu added! </div>');
			redirect('menu');
		}
	}

	public function submenu()
	{
		// Dapatkan Data Dari Tabel User.
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		$data['title'] = "Submenu Management";
		// Load Model Menu
		$this->load->model('Menu_model', 'menu');
		// Dapatkan Data Dari model method subMenu.
		$data['subMenu'] = $this->menu->getSubMenu();
		// Dapatkan Data Dari Tabel User Menu.
		$data['menu'] = $this->db->get('user_menu')->result_array();
		// Berikan Rule Untuk Data Dari Input Modal Add New Submenu.
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('menu_id', 'Menu', 'required');
		$this->form_validation->set_rules('url', 'URL', 'required');
		$this->form_validation->set_rules('icon', 'Icon', 'required');
		// Validasi Data Dari Modal Add New Submenu.
		if ($this->form_validation->run() == false)
		{
			$this->load->view('templates/header', $data);
			$this->load->view('templates/sidebar', $data);
			$this->load->view('templates/topbar', $data);
			$this->load->view('menu/submenu', $data);
			$this->load->view('templates/footer');
		} else
		{
			// Ambil Data Dari Inputan Modal Add New Submenu.
			$data = [
						'title' => $this->input->post('title'),
						'menu_id' => $this->input->post('menu_id'),
						'url' => $this->input->post('url'),
						'icon' => $this->input->post('icon'),
						'is_active' => $this->input->post('is_active')
			];
			// Simpan $data Ke Tabel User Sub Menu.
			$this->db->insert('user_sub_menu', $data);
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			New submenu added! </div>');
			redirect('menu/submenu');
		}

	}
}