<?php if (!defined('BASEPATH')) exit('No direct access allowed');
class Bo extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$num =40000000000001;
		//echo substr($num, -3);die;
		
	}
	function index(){
		$this->m_master->get_login();		
		$data['chart1'] = $this->chart1();
		$this->load->view('bo/v_header');
		$this->load->view('bo/dashboard/v_dashboard',$data);
		$this->load->view('bo/v_footer');
	}
	/*
	 * menampilkan presentase yg sudah distok dan belum distok
	 */
	function chart1(){
		
		$year='';
		
		$main_asset = count($this->m_master->get_master_produk());
		$this->db->group_by('DATE_FORMAT(B.date_add,"%Y")',date('Y'));
		//mendapatkan data stock take yang sudah discan dilihat dari tanggal scan yang tidak kosong
		$com = $this->m_master->get_stock_take(array('B.date_add !='=>null,'B.not_found'=>0));		
		//mendapatkan data stock yang belum discan dilihat tanggal scan yg masih kosong
		$out = $this->m_master->get_stock_take(array('B.date_add ='=>null));		
		//jika tidak ada stock opname maka diset tahun sekarang
		$year .= isset($com[0]->dateadd) ? date('Y',strtotime($com[0]->dateadd)) : date('Y');
			
		$data['title'] = 'Complete Vs Outstanding Opname';
		$data['month'] = "'".$year."'";
		$data['complete'] = number_format(count($com),2);
		$data['outstanding'] = number_format(count($out),2);
		$data['mainasset'] = number_format($main_asset,2);		
		return $this->load->view('dashboard/v_chart1',$data,true);
	}
	function destroy(){
		$this->session->sess_destroy();
	}
		
}
