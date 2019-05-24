<?php 

function is_logged_in()
{
	// Ambil Library Codeigniter
	$ci = get_instance();
	// Kondisi Untuk Tendang User Yang Mencoba Masuk Secara Paksa Dan Akses Sidebar Sesuai Role Id.
	if(!$ci->session->userdata('email'))
	{
		redirect('auth');
	} else
	{
		
	}
}