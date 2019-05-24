<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller 
{
	// Kondisi Untuk Tendang User Yang Mencoba Masuk Secara Paksa Dan Akses Sidebar Sesuai Role Id.
	public function __construct()
	{
		parent::__construct();
		is_logged_in();
	}

	public function index(){
		$data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
		
		$data['title'] = "Dashboard";
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar', $data);
		$this->load->view('templates/topbar', $data);
		$this->load->view('admin/index', $data);
		$this->load->view('templates/footer');
	}

}