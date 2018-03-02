<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class M_master extends CI_Model{
	var $company_code;
	var $kd_group;
	var $id_user;
	public function __construct(){
		parent::__construct();
		$this->company_code = $this->session->userdata('company_code');
		$this->kd_group = $this->session->userdata('kd_group');
		$this->id_user = $this->session->userdata('id_user');
	}
	function insertdata($table,$post){
		$res = $this->db->insert($table,$post);
		return $res;
	}	
	function updatedata($table,$data,$where){
		$res = $this->db->update($table,$data,$where);
		return $res;
	}
	function deletedata($table,$where=''){
		if (!empty($where)) {
			$this->db->where($where);
			$res = $this->db->delete($table);
			
		}else{
			$res  = $this->db->empty_table($table);
		}
		
		return $res;
	}
	function cek_login($post){
		$sql = $this->db->select('*')
						->from	('tb_user')						
						->where	($post)
						->where	('deleted',1)
						->where	('active',1)
						->get()->result();
		return $sql;
	}
	function get_login(){
		if ($this->session->userdata('login_admin') == false){
			redirect('bo/mplogin');
		}
	}
	function get_akses_modul($where){		
		$kd_group = $this->session->userdata('kd_group');
		$sql = $this->db->select('A.*,B.*')
					->from  ('tb_akses as A')
					->join	('tb_modul as B','A.id_modul = B.id_modul','left')
					->where	('A.kd_group',$kd_group)
					->where	('A.active',1)
					->where ($where)
					//->where	('B.id_modul_parent',0)
					//->where	('B.level',0)
					->order_by('B.sort','asc')
					->get();
		return $sql;
	}
	function get_table($table){
		return $this->db->get($table);
	}
	function get_table_column($column,$table,$where=''){
			$this->db->select($column);
			$this->db->from($table);
			($where != '') ? $this->db->where($where) : '';
		return $this->db->get()->result();
	}
	function get_table_filter($table,$where='',$column='',$sort='',$length='',$start=''){		
		$this->db->select('*');
		$this->db->from($table);
		(!empty($where)) ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql = $this->db->get()->result();		
		return $sql;
	}
	function get_akses_all(){
		//tidak menampilkan superadmin jika group bukan SA		
		if ($this->session->userdata('kd_group') != 'SA')
		{
			$this->db->where('kd_group <>','SA');
		}
		return $this->db->get('tb_group');
	}	
	function get_karyawan($where='',$column='',$sort='',$length='',$start=''){
			 $this->db->select('A.*,B.nama_jabatan,C.nama_bagian,D.nama_group');
			 $this->db->from	('tb_karyawan as A');
			 $this->db->join	('tb_jabatan as B','A.id_jabatan = B.id_jabatan','left');
			 $this->db->join	('tb_bagian as C','A.id_bagian = C.id_bagian','left');	
			 $this->db->join	('tb_group D','A.kd_group = D.kd_group','left');
			 $this->db->where	('A.deleted',1);
			 /*where digunakan untuk get by field*/
			 ($where !='') ? $this->db->where($where) : '';			 
			 $this->db->limit($length,$start);
			 $this->db->order_by($column,$sort);			
			 $sql =  $this->db->get()->result();
		return $sql;
	}
	/*
	 * mengakftifkan mungsi search by keyword
	 */
	function search_like($keyword,$column){		
		//melakukan pengulangan column
		$this->db->group_start();
		foreach ($column as $val=>$row){
			$this->db->or_like($row,$keyword);
		}
		$this->db->group_end();
	}
	/*Menangkap semua data yang dikirimkan oleh client*/
	function request_datatable(){
		/*Offset yang akan digunakan untuk memberitahu database
		 dari baris mana data yang harus ditampilkan untuk masing masing page
		 */
		$start = $_REQUEST['start'];	
		/*Keyword yang diketikan oleh user pada field pencarian*/
		$keyword = $_REQUEST['search']["value"];
		/*Sebagai token yang yang dikrimkan oleh client, dan nantinya akan
		 server kirimkan balik. Gunanya untuk memastikan bahwa user mengklik paging
		 sesuai dengan urutan yang sebenarnya */
		$draw = $_REQUEST['draw'];	
		/*asc/desc yg direquest dari client*/
		$sorting = $_REQUEST['order'][0]['dir'];	
		/*index column yg direquest dari client*/
		$column = $_REQUEST['order'][0]['column'];	
		/*Jumlah baris yang akan ditampilkan pada setiap page*/
		$length = $_REQUEST['length'];
		
		//tanggal from index 0 get by id
		$date_from = $_REQUEST['columns'][0]['search']['value'];
		//tanggal to index 1 get by id
		$date_to = $_REQUEST['columns'][1]['search']['value'];
		$output = array('start'=>$start,'keyword'=>$keyword,
					   'draw'=>$draw,'sorting'=>$sorting,
					   'column'=>$column,'length'=>$length,
					   'date_from'=>$date_from,'date_to'=>$date_to
				
		);
		return $output;
	}
	
	function get_moduls($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.id_modul,A.nama_modul,A.level,A.akses_code,B.nama_modul as nama_parent');
		$this->db->from	('tb_modul as A');
		$this->db->join	('tb_modul as B','A.id_modul = B.id_modul_parent','left');
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;		
	}
	
	
	function get_stock_move($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('*');
		$this->db->from	('tb_stock_take');		
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';		
		$this->db->group_by('product_code');		
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
		
	}
	function get_stock_take($where='',$column='',$sort='',$length='',$start=''){
		//menampilkan data stock take yang paling terakhir discan
		$this->db->select('A.*,MAX(B.date_add) as dateadd,B.id_stock_take,B.add_by,C.nama_class');
		$this->db->from('tb_product as A');
		$this->db->join('tb_stock_take as B','A.uniqe_code = B.uniqe_code','left');		
		$this->db->join('tb_asset_class as C','A.asset_class = C.asset_class','left');
		$this->db->where('A.deleted',1);//tidak ditampilkan jika asset sudah didelete		
		$this->db->group_by ('A.product_code,A.company_code');		
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->akses_asset('A');
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_stock_log($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select  ('A.*,B.description,B.asset_class,B.company_code,
							 B.bisnis_area,B.sub_location,B.deleted,C.nama_class');
		$this->db->from    ('tb_stock_take as A');
		$this->db->join	   ('tb_product as B','A.uniqe_code = B.uniqe_code','left');
		$this->db->join	   ('tb_asset_class as C','B.asset_class = C.asset_class','left');
		$this->akses_asset('B');
		//$this->db->where   ('B.deleted',1);
		//$this->db->group_by ('DATE_FORMAT(A.date_add,"%Y-%m-%d")');
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function export_stock_log(){		
	    $this->db->select  ('B.product_code,B.description,B.asset_class,C.nama_class,B.company_code,
							 B.bisnis_area,B.sub_location,A.date_add,A.add_by,');						   
		$this->db->from    ('tb_stock_take as A');
		$this->db->join	   ('tb_product as B','A.product_code = B.product_code','left');
		$this->db->join	   ('tb_asset_class as C','B.asset_class = C.asset_class','left');
		$this->akses_asset('B');
		//$this->db->where   ('B.deleted',1);			
		$this->db->order_by('A.product_code','asc');
		$sql =  $this->db->get();
		return $sql;
	}
	function export_stock_take($where=''){
		//menampilkan data stock take yang paling terakhir discan
		$this->db->select('A.product_code,A.description,A.asset_class,C.nama_class,A.company_code,A.bisnis_area,
						   A.sub_location, MAX(B.date_add) as date_add,B.add_by');		
		$this->db->from('tb_product as A');
		$this->db->join('tb_stock_take as B','A.product_code = B.product_code','left');
		$this->db->join('tb_asset_class as C','A.asset_class = C.asset_class','left');
		$this->db->where('A.deleted',1);
		$this->db->group_by ('product_code');
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('B.product_code','asc');
		$sql =  $this->db->get();
		return $sql;
	}
	function export_stocktake_master($where=''){
		//menampilkan data stock take yang paling terakhir discan
		$this->db->select('A.product_code,A.description,A.asset_class,A.company_code,A.bisnis_area,
						   A.sub_location');
		$this->db->from('tb_product as A');
		$this->db->join('tb_stock_take as B','A.product_code = B.product_code','left');
		$this->db->join('tb_asset_class as C','A.asset_class = C.asset_class','left');
		$this->db->where('A.deleted',1);
		$this->db->group_by ('product_code');	
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->order_by('B.product_code','asc');
		$sql =  $this->db->get();
		return $sql;
	}
	function export_product(){
		$this->db->select  ('A.product_code,A.description,A.asset_class,B.nama_class,
						     A.company_code,A.bisnis_area,A.sub_location,A.date_add,A.add_by');
		$this->db->from    ('tb_product as A');		
		$this->db->join	   ('tb_asset_class as B','A.asset_class = B.asset_class','left');	
		$this->db->order_by('product_code','asc');
		$sql =  $this->db->get();
		return $sql;
	}
	function export_notfound($where=''){
		//menampilkan data stock yg tidak ditemukan dan ditampilkan sesuai userlogin
		$this->db->select('A.product_code,A.remarks,A.add_by,B.company_code,A.date_add');
		$this->db->from('tb_stock_take as A');
		$this->db->join('tb_user as B','A.id_user = B.id_user','left');
		$this->db->group_by ('A.product_code');
		$this->db->where('A.not_found',1);//asset tidak ditemukan di database, tetapi fisik ada
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->akses_asset('B');		
		$this->db->order_by('A.product_code','asc');
		$sql =  $this->db->get();
		return $sql;
	}
	function generate_export($to,$filename,$sql,$title,$column){
		if ($to == 'csv'){
			$this->load->dbutil(); // call db utility library
			$this->load->helper('download'); // call download helper
			$file_name = $filename.'.csv';
			$delimiter = ";";
			$newline = "\r\n";//baris baru
			$enclosure = '';//tanda kutip
			//remove firts line (headername)
			$convert = ltrim(strstr($this->dbutil->csv_from_result($sql,$delimiter,$newline,$enclosure), $newline));
			force_download($file_name, $convert);
		}else if ($to == 'excel'){
			$this->load->helper('to_excel');
			$file_name = $filename;
			to_excel_custom($sql,$file_name,$column);
		}else if ($to == 'pdf'){
			error_reporting(1);
			$parameters = array (
					'paper'=>'A4',
					'orientation'=>'landscape',
					'type'=>'',
					'options'=>'',
			);
			$this->load->library('Pdf', $parameters);
			//path font set
			$this->pdf->selectFont(APPPATH.'/third_party/pdf-php/fonts/FreeSerif.afm');
			$this->pdf->ezImage(base_url('assets/bo/images/logo/logo2.jpg'), 0, 250, 'none', 'left');
			$this->pdf->ezText($title, 14, array('justification'=> 'centre'));
			$this->pdf->ezSetDy(-10);//spasi
			$this->pdf->ezText(short_date($this->datenow), 14, array('justification'=> 'centre'));
			$this->pdf->ezSetDy(-15);
			$no = 1;
			foreach ($sql->result_array() as $key=>$value){
				$data[$key] = $value;
				$data[$key]['no'] = $no++;
		
			}			
			$this->pdf->ezTable($data, $column);
			$file_name = $filename.'.pdf';
			$this->pdf->ezStream(array('Content-Disposition'=>$file_name));
		}else{
			echo 'Error export';
			return false;
		}
	}
	function get_priv($ac,$action){
		$notif='';
		$alias_array  = array('view'=>'Menampilkan halaman','add'=>'Tambah baru',
							  'edit'=>'Ubah data','delete'=>'Hapus data','active'=>'Akses modul');
		$kd_group = $this->session->userdata('kd_group');
		$this->db->select ('A.active,A.add,A.edit,A.delete,A.view,B.nama_modul,B.kd_modul');
		$this->db->from   ('tb_akses as A');
		$this->db->join	  ('tb_modul as B','A.id_modul = B.id_modul','left');
		$this->db->where  ('A.kd_group',$kd_group);
		$this->db->where  ('B.kd_modul',$ac);
		$sql = $this->db->get()->result();
		foreach ($sql as $row)
			if ($row->$action != 1){
			$data['notif'] = 'Anda tidak punya hak untuk '.$alias_array[$action].' '.$row->nama_modul;
			$data['error'] = 'v_access_denied';
			return $data;
		}
	}
	function get_modul_group($where){
		$sql = $this->db->select('A.nama_modul,A.link,A.kd_modul,
								  B.id_akses,B.kd_group,B.id_modul,B.active,B.add,B.edit,B.delete,B.view')
						->from	('tb_modul as A')
						->join	('tb_akses as B','A.id_modul = B.id_modul','left')
						->where	($where)
						->get();
		return $sql;
	}
	function get_user($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_group');
		$this->db->from('tb_user as A');
		$this->db->join('tb_group as B','A.kd_group = B.kd_group','left');		
		$this->db->where('A.deleted',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
	function get_master_produk($where='',$column='',$sort='',$length='',$start=''){
		$this->db->select('A.*,B.nama_class');
		$this->db->from('tb_product as A');
		$this->db->join('tb_asset_class as B','A.asset_class = B.asset_class','left');
		(!empty($where)) ? $this->db->where($where) : '';
		$this->akses_asset('A');
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql = $this->db->get()->result();		
		return $sql;
		
	}
	function akses_asset($init){
		//jika login bukan sebagai superadmin dan admin
		if ($this->kd_group != 'SA' && $this->kd_group != 'ADM'){
			$this->db->where($init.'.company_code',$this->company_code);
		}
	}
	function group_akses(){
		//jika login bukan sebagai superadmin dan admin
		if ($this->kd_group != 'SA' && $this->kd_group != 'ADM'){
			return false;
		}else{
			return true;
		}
	}
	function get_notfound($where='',$column='',$sort='',$length='',$start=''){
		//menampilkan data stock yg tidak ditemukan dan ditampilkan sesuai userlogin
		$this->db->select('A.*,B.nama_depan,B.company_code');
		$this->db->from('tb_stock_take as A');		
		$this->db->join('tb_user as B','A.id_user = B.id_user','left');	
		$this->db->group_by ('A.product_code');
		$this->db->where('A.not_found',1);
		/*where digunakan untuk get by field*/
		($where !='') ? $this->db->where($where) : '';
		$this->akses_asset('B');
		$this->db->limit($length,$start);
		$this->db->order_by($column,$sort);
		$sql =  $this->db->get()->result();
		return $sql;
	}
}
