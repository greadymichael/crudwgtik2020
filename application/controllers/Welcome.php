<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Guru');
		$this->load->model('Siswa');
	}

	public function index()
	{
		if (!$this->session->userdata('credentials')) :
			if ($this->input->post('email') && $this->input->post('password')) :
				$con['returnType'] = 'count';
				$con['conditions'] = array(
					'email' => $this->input->post('email'),
					'password' => md5($this->input->post('password')),
				);
				$cek_user = $this->Guru->getData($con);
				if ($cek_user == 1) :
					$info_akun = $this->Guru->getData(array('email' => $this->input->post('email')));
					$this->session->set_userdata('credentials', $info_akun);
					redirect(base_url());
				else :
					$data['notif_error'] = 'Email/Password Salah mohon inputkan ulang.';
				endif;
				$this->load->view('login', $data);
			else :
				$this->load->view('login');
			endif;
		else :
			$con['returnType'] = 'count';
			$data['guru'] = $this->Guru->getData($con);
			$con['returnType'] = 'count';
			$con['conditions'] = array(
				'jenis_kelamin' => "Laki-Laki",
			);
			$data['lakilaki'] = $this->Siswa->getData($con);
			$con['conditions'] = array(
				'jenis_kelamin' => "Wanita",
			);
			$data['perempuan'] = $this->Siswa->getData($con);
			$con['conditions'] = array(
				'status' => "Aktif",
			);
			$data['total_siswa'] = $this->Siswa->getData($con);
			$data["semua_siswa"] = $this->Siswa->getData();
			$ses = $this->session->userdata('credentials')[0];
			$data['user'] = $this->Guru->getData(array('email' => $ses['email']))[0];
			$this->load->view('welcome_message', $data);
		endif;
	}

	public function tambah()
	{
		if (!$this->session->userdata('credentials')) :
			redirect(base_url());
		else :
			$ses = $this->session->userdata('credentials')[0];
			if ($this->input->post('nama') && $this->input->post('tempat') && $this->input->post('tgl') && $this->input->post('kelamin') && $this->input->post('angkatan')) :
				$sql = array(
					'nama_lengkap' => $this->input->post('nama'),
					'tempat_lahir' => $this->input->post('tempat'),
					'tanggal_lahir' => $this->input->post('tgl'),
					'angkatan' => $this->input->post('angkatan'),
					'jenis_kelamin' => $this->input->post('kelamin'),
					'status' => "Aktif",
				);
				$insert = json_decode($this->Siswa->insert($sql));
				if ($insert) :
					$data['sukses'] = TRUE;
				else :
					$data['error'] = TRUE;
				endif;
				$data['user'] = $this->Guru->getData(array('email' => $ses['email']))[0];
				$this->load->view('tambah', $data);
			else :
				$data['user'] = $this->Guru->getData(array('email' => $ses['email']))[0];
				$this->load->view('tambah', $data);
			endif;
		endif;
	}

	public function edith() {
		$this->load->view('tambah');
	}
}
