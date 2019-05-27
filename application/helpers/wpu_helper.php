<?php

function is_logged_in()
{
	// ambil library codeigniter
	$ci = get_instance();
	// kondisi untuk tendang user yang mencoba masuk secara paksa dan akses sidebar sesuai role id.
	if (!$ci->session->userdata('email')) {
		redirect('auth');
	} else {
		//cek role id..
		$role_id = $ci->session->userdata('role_id');
		// cek sekarang ada dimenu apa.
		$menu = $ci->uri->segment(1);
		// query database menu .
		$queryMenu = $ci->db->get_where('user_menu', ['menu' => $menu])->row_array();
		// dapatkan id menu.
		$menu_id = $queryMenu['id'];
		// cek apakah boleh user mengakses menu itu..
		$userAccess = $ci->db->get_where('user_access_menu', [
			'role_id' => $role_id,
			'menu_id' => $menu_id
		]);
		// berikan kondisi jika user boleh atau tidak boleh mengakses menu tersebut.
		if ($userAccess->num_rows() < 1) {
			redirect('auth/blocked');
		}
	}
}

function check_access($role_id, $menu_id)
{
	// ambil library codeigniter
	$ci = get_instance();
	// ambil data dari tabel role_id, menu_id dan user_access_menu lalu cocokan dengan parameter
	$ci->db->where('role_id', $role_id);
	$ci->db->where('menu_id', $menu_id);
	$result = $ci->db->get('user_access_menu');
	// berikan kondisi jika ceklis atau tidak ceklis
	if ($result->num_rows() > 0) {
		return "checked='checked'";
	}
}
