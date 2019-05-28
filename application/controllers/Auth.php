<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}

	public function index()
	{
		//tendang user yang sudah login untuk kembali kehalaman login
		if ($this->session->userdata('email')) {
			redirect('user');
		}
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'Login Page';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/login');
			$this->load->view('templates/auth_footer');
		} else {
			$this->_login();
		}
	}

	private function _login()
	{
		// Ambil Data Dari Form Input.
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		// Cocokan Dengan Database.
		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		// Beri Kondisi Jika Ada Dan Tidak Ada User.
		if ($user) {
			// Bila Data User Ada Dan Berstatus Aktip.
			if ($user['is_active'] == 1) {
				// Status User Aktif Lalu Cek Password.
				if (password_verify($password, $user['password'])) {
					$data =
						[
							'email' => $user['email'],
							'role_id' => $user['role_id']
						];
					$this->session->set_userdata($data);
					// Cek Role Id.
					if ($user['role_id'] == 1) {
						redirect('admin');
					} else {
						redirect('user');
					}
				} else {
					// Jika Password Salah.
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Wrong password </div>');
					redirect('auth');
				}
			} else {
				// Status User Tidak Aktif.
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				This email has not been activated! </div>');
				redirect('auth');
			}
		} else {
			// Bila Data User Tidak Ada Berikan Alert.
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
			Email is not registered! </div>');
			redirect('auth');
		}
	}

	public function registration()
	{
		//tendang user yang sudah login untuk kembali kehalaman login
		if ($this->session->userdata('email')) {
			redirect('user');
		}
		$this->form_validation->set_rules('name', 'Name', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
			'matches' => 'Password dont match!',
			'min_length' => 'Password too short!'
		]);
		$this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

		if ($this->form_validation->run() == false) {
			$data['title'] = 'WPU User Registration';
			$this->load->view('templates/auth_header', $data);
			$this->load->view('auth/registration');
			$this->load->view('templates/auth_footer');
		} else {
			$email = $this->input->post('email', true);
			$data =
				[
					'name' => htmlspecialchars($this->input->post('name', true)),
					'email' => htmlspecialchars($email),
					'image' => 'default.jpg',
					'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
					'role_id' => 2,
					'is_active' => 0,
					'data_created' => time()
				];
			// siapkan token
			$token = base64_encode(random_bytes(32));
			//siapkan user token
			$user_token = [
				'email' => $email,
				'token' => $token,
				'date_created' => time()
			];

			$this->db->insert('user', $data);
			$this->db->insert('user_token', $user_token);
			// setelah disimpan kirim email aktivasi ke user
			$this->_sendEmail($token, 'verify');
			$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
			Congratulation! your account has been  created. Please activate your account! </div>');
			redirect('auth');
		}
	}

	private function _sendEmail($token, $type)
	{
		// buat config email
		$config = [
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_user' => 'Kumpulanlink9302@gmail.com',
			'smtp_pass' => '!%)$(#150493!%)$(#',
			'smtp_port' => 465,
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => "\r\n"
		];
		// panggil library email codeigniter
		$this->email->initialize($config);
		// dari siapa email akan dikirimkan
		$this->email->from('Kumpulanlink9302@gmail.com', 'Syuhada');
		// masukan alamat email peneriam
		$this->email->to($this->input->post('email'));
		// kondisi jika typenya adalah verify
		if ($type == 'verify') {
			// tuliskan subjek email
			$this->email->subject('Account Verification');
			// tuliskan isi email
			$this->email->message('Click this link to verify you account : <a href="' . base_url() . 'auth/verify?email=' . $this->input->post('email') . '& token=' . $token . '">Activate</a>');
		}
		// kirim email
		if ($this->email->send()) {
			return true;
		} else {
			echo $this->email->print_debugger();
			die;
		}
	}


	public function verify()
	{
		// ambil data email dan token di link email activation
		$email = $this->input->get('email');
		$token = $this->input->get('token');
		//ambil data email dari database
		$user = $this->db->get_where('user', ['email' => $email])->row_array();
		// kondisi jika emal atau user ada didatabase
		if ($user) {
			$user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();
			if ($user_token) {
				// kondisi bila kurang dari satu hari berarti user masih bisa daftar
				if (time() - $user_token['date_created'] < (60 * 60 * 24)) {
					// query simpan data user
					$this->db->set('is_active', 1);
					$this->db->where('email', $email);
					$this->db->update('user');
					// delete user token
					$this->db->delete('user_token', ['email' => $email]);
					// tampilkan pesan bila token tidak sesuai datab~ase
					$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">'
						. $email . ' has been activated! Please Login.</div>');
					redirect('auth');
				} else {
					// hapus data user didatabase
					$this->db->delete('user', ['email' => $email]);
					// hapus data user token ~
					$this->db->delete('user_token', ['email' => $email]);
					// tampilkan pesan bila token tidak sesuai database
					$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
					Account activation failed! Token expired. </div>');
					redirect('auth');
				}
			} else {
				// tampilkan pesan bila token tidak sesuai database
				$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
				Account activation failed! Wrong token. </div>');
				redirect('auth');
			}
		} else {
			// tampilkan pesan bila email tidak sesuai database
			$this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
			Account activation failed! Wrong email. </div>');
			redirect('auth');
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('role_id');
		$this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
		You have been logged out! </div>');
		redirect('auth');
	}

	public function blocked()
	{
		$this->load->view('auth/blocked');
	}
}
