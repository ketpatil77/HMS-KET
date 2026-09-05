<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	private $body_Data;

	function __construct()
	{
		parent::__construct();
		if(!is_login()){
			redirect(base_url('login'));
		}
		$this->body_Data = array();
		$this->load->model(array('Doctor_model','Invoice_model','Hospital_model'));
	}

	public function index()
	{
		$doctors = $this->Doctor_model->Get();
		$this->Hospital_model->set_table('appoinment');
		$appointments = $this->Hospital_model->Get_Data();
		$this->body_Data['title'] = 'Dashboard';
		$this->body_Data['stats'] = array(
			array('label' => 'Doctors', 'value' => count($doctors), 'trend' => '+4%', 'tone' => 'success'),
			array('label' => 'Appointments', 'value' => count($appointments), 'trend' => '+9%', 'tone' => 'warning'),
			array('label' => 'Revenue', 'value' => count($this->Invoice_model->Get()), 'trend' => '+17%', 'tone' => 'danger'),
		);
		$this->body_Data['recent_activity'] = array_slice($appointments, 0, 8);
		$this->load->view('header', $this->body_Data);
		$this->load->view('dashboard', $this->body_Data);
		$this->load->view('footer');
	}
}
