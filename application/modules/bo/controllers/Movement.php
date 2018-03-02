<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Movement extends  CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'MOVEM';
		$this->table = 'tb_stock_take';
		$this->company_code = $this->session->userdata('company_code');
		$this->id_user = $this->session->userdata('id_user');
		
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $table;
	var $company_code;
	var $id_user;
	function index(){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/movement/v_index_movement' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['getuser'] = $this->m_master->get_user_use();
		$data['page_title'] = "Assets Movement Outstanding";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'B.date_add',//default order sort
				1 => 'A.product_code',
				2 => 'A.description',
				3 => 'A.asset_class',
				4 => 'C.nama_class',
				5 => 'A.company_code',
				6 => 'A.bisnis_area',
				7 => 'A.sub_location',				
				8 =>'B.not_found'
		);
		return $column_array;
	}
	function get_records($code){
		/*
		 * mengkondisikan barang yang sudah distok dan belum distok
		* val == 0 //outstanding
		* val == 1 //complete
		*/
		$where =  array('B.date_add'=>null,'A.company_code'=>$code);
	
		/*Mempersiapkan array tempat kita akan menampung semua data
		 yang nantinya akan server kirimkan ke client*/
		$output=array();
		/*data request dari client*/
		$request = $this->m_master->request_datatable();
	
		/*Token yang dikrimkan client, akan dikirim balik ke client*/
		$output['draw'] = $request['draw'];
	
		/*Menghitung total desa didalam database*/
		$total = count($this->m_master->get_stock_take($where));
		$output['recordsTotal']= $output['recordsFiltered'] = $total;
	
		/*disini nantinya akan memuat data yang akan kita tampilkan
		 pada table client*/
		$output['data'] = array();
	
	
		/*
		 * jika keyword tidak kosong, maka menjalankan fungsi search
		* untuk ditampilkan di datable
		* */
		if($request['keyword'] !=""){
			/*menjalankan fungsi filter or_like*/
			$this->m_master->search_like($request['keyword'],$this->column());
		}
	
		/*Pencarian ke database*/
		$query = $this->m_master->get_stock_take($where,$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_stock_take($where));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$status = '<span class="label label-danger">Out Stand</span>';
			$action = '<a href="#modal-form" role="button" title="Move" data-toggle="modal" class="btn btn-info btn-circle" onclick="modalShow(\''.base_url('bo/'.$this->class.'/modal_move').'\',\''.$row->id_product.'\',\'modal-form\')"><i class="icon-exchange"></i> </a>';
			$output['data'][]=array($nomor_urut,
					$row->product_code,
					$row->description,
					$row->asset_class,
					$row->nama_class,
					$row->company_code,
					$row->bisnis_area,
					$row->sub_location,										
					$status,
					$action
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function tab_onclick(){
		$val = explode("#", $this->input->post('value'));
		echo $this->m_master->table_move($val[0],$val[1]);
	}
	function modal_move(){
		$res="";
		$id = $this->input->post('val');
		$sql = $this->m_master->get_table_filter('tb_product',array('id_product'=>$id));
		foreach ($sql as $row)
			foreach ($row as $key=>$val){
			$data[$key] = $val;
		}
		$data['getuser'] = $this->m_master->get_user_use();
		$data['assetclass'] = $this->db->get('tb_asset_class')->result();
		$data['acces_code'] = $this->acces_code;
		$res .= $this->load->view('movement/v_modal_move',$data,true);
		echo $res;
	}
	function save_move(){
		$post = $this->input->post();
		$id= $post['id'];//for update	
		$companycode = $post['companycode'];
		$number = $post['itemnumber'];	
		$uniqecode = $number.$companycode;	
		$put['uniqe_code'] = $uniqecode;
		//$put['description'] = $post['desc'];
		//$put['asset_class'] = $post['assetclass'];		
		$put['company_code'] = $companycode;
		//$put['bisnis_area'] = $post['bisnisarea'];
		$put['sub_location'] = $post['sublocation'];
		$put['date_update'] = $this->datenow;
		$put['add_update'] = $this->addby;		
		
		$where = array('company_code'=>$companycode,'product_code'=>$number);
		$cek = $this->m_master->get_table_filter('tb_product',$where);
		if (count($cek) > 0){
			//jika sudah ada tidak dapat update data
			echo json_encode(array('error'=>1,'type'=>'error','msg'=>'Asset tidak dapat dipindahkan, data sudah ada!'));
		}else{
			$res = $this->m_master->updatedata('tb_product',$put,array('id_product'=>$id));
			if ($res > 0){				
				/*
				 * jika asset berhasil dipindahkan, maka data yg tidak ditemukan akan dihapus
				 */
				//menghapus data yg tidak ditemukan
				$this->m_master->deletedata('tb_stock_take',array('uniqe_code'=>$uniqecode));
				//$this->session->set_flashdata('success','Ubah produk berhasil.');
				echo json_encode(array('error'=>0,'type'=>'modal','msg'=>'Asset berhasil dipindahkan!'));
					
			}
		}
		
	}
}
