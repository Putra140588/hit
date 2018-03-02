<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Deletelog extends  CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'PROD';
		$this->table = 'tb_product';
		
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $table;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/produk/v_index_deletelog' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];		
		$data['page_title'] = "Main Assets Delete Log";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	
}
