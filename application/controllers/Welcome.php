<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->database();
	}

	public function index()
	{
		$this->load->view('vlogin');
	}

	public function proseslogin() {
		$usr = $this->input->get('username', TRUE);
		$psw = $this->input->get('password', TRUE);
		
		
		// Menggunakan Query Builder agar lebih aman dari SQL Injection
		$cek = $this->db->where('user_name', $usr)
						->where('pass', md5($psw))
						->get('m_pass');
	
		if ($cek->num_rows() > 0) {
			// Login berhasil, buat session
			$val = $cek->row_array(); // Ambil satu baris data
			$sess_data = array(
				'username' => $val['user_name'],
				'nama' => $val['nama']
			);
			$this->session->set_userdata($sess_data);
			redirect('Welcome/sukses');
		} else {
			// Login gagal, kirim pesan error
			$this->session->set_flashdata('result_login', 'Username atau Password yang anda masukkan salah.');
			redirect('Welcome/');
		}
	}

	function sukses() {
        
		$data = array(
            'username' => $this->session->userdata('username'),
        );
	
        $this->load->view('general/header');
        $this->load->view('general/sidebar');
        $this->load->view('home',$data);
        $this->load->view('general/footer');
    }
	
    function logout() {
        $this->session->sess_destroy();
        redirect('Welcome/');
    }
	
}
