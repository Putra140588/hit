<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Stocktake extends  CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'STKE';
		$this->table = 'tb_stock_take';
		
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $table;
	function index(){
		$this->m_master->get_login();	
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/stock/v_index_stock_take' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Stock Opname";
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
				8 => 'B.date_add',
				9 => 'B.add_by',
		);
		return $column_array;
	}
	function get_records($val){
		/*
		 * mengkondisikan barang yang sudah distok dan belum distok
		 * val == 0 //outstanding
		 * val == 1 //complete
		 */
		$where = ($val == 0) ? array('B.date_add'=>null) : array('B.date_add !='=>null);
		
		/*Mempersiapkan array tempat kita akan menampung semua data
		 yang nantinya akan server kirimkan ke client*/
		$output=array();
		/*data request dari client*/
		$request = $this->m_master->request_datatable();
	
		/*Token yang dikrimkan client, akan dikirim balik ke client*/
		$output['draw'] = $request['draw'];		
	
		/*
		 $output['recordsTotal'] adalah total data sebelum difilter
		$output['recordsFiltered'] adalah total data ketika difilter
		Biasanya kedua duanya bernilai sama pada saat load default(Tanpa filter), maka kita assignment
		keduaduanya dengan nilai dari $total
		*/
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
		}elseif ($request['date_from'] != "" && $request['date_to'] == "" && $val == 1){
			$this->db->like('B.date_add',$request['date_from']);
		}elseif ($request['date_from'] != "" && $request['date_to'] != "" && $val == 1){
			$this->db->where('DATE_FORMAT(B.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(B.date_add,"%Y-%m-%d") <=',$request['date_to']);
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
		}elseif ($request['date_from'] != "" && $request['date_to'] == "" && $val == 1){
			/*jika filter tgl from & tgl to & table complete opname*/
			$this->db->like('B.date_add',$request['date_from']);			
			$total = count($this->m_master->get_stock_take($where));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != "" && $val == 1){
			$this->db->where('DATE_FORMAT(B.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(B.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_stock_take($where));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}	
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
					$row->product_code,				
					$row->description,	
					$row->asset_class,
					$row->nama_class,
					$row->company_code,
					$row->bisnis_area,
					$row->sub_location,
				    ($row->dateadd == null ) ? 'N/A' : $row->dateadd,	
					($row->add_by == null ) ? 'N/A' : $row->add_by
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'add');
		$main_page = (empty($priv)) ? 'bo/stock/v_crud_stock_take' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Tambah Stock Take";
		$data['class'] = $this->class;			
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	/*
	 * importing proses
	 */
	function proses(){
		/*
		 * importing file txt || csv
		 */
		$file = $_FILES['stocktake']['tmp_name'];
		$expl = explode('.',$_FILES['stocktake']['name']);
		$end_param = end($expl);
		if ($end_param == 'TXT' or $end_param == 'csv'){
			if ($file) {
		    $handle = fopen($file,"r");          //  Open the file and read     
		    $no=0;          
		    $error=0;		   
		    while($fileimport = fgetcsv($handle, 10000, ";")) {//To get Array from CSV, " "(delimiter) 	
		    	$string[] = implode("|", array_filter($fileimport));    	
		    		$input['product_code'] = $fileimport[0];		    		
		    		$input['date_add'] = $fileimport[1];
		    		$input['add_by'] = $this->addby;
		    		$datapost[] = $input;		    				    		
		    }   
		   echo '<pre>'; print_r($string);die;
			//$res = $this->db->insert_batch('tb_stock_take',$datapost);
			if ($res > 0){
			    	$this->session->set_flashdata('success','Tambah stock take berhasil!');
			    	redirect('bo/'.$this->class);
			    }			   
			}  
		}else{
			$this->session->set_flashdata('danger','Tidak dapat import data, type file bukan TXT atau CSV.');
			redirect('bo/'.$this->class);
		}
	}
	
	function export($type,$to){		
		if ($type == 'complete'){
			$where = array('B.date_add !='=>null);
			$filename = 'complete_opname('.date('d-m-Y',strtotime($this->datenow)).')';
			$title = 'COMPLETE OPNAME';
		}else{
			$where = array('B.date_add'=>null);
			$filename = 'outstanding_stock('.date('d-m-Y',strtotime($this->datenow)).')';
			$title = 'OUTSTANDING OPNAME';
		}		
		$sql = $this->m_master->export_stock_take($where);
		$column_header = array( 	
				'no' => 'No',
				'product_code' => 'Sub Number',
				'description' => 'Description',
				'asset_class' => 'Asset Class',
				'nama_class' => 'Jenis',
				'company_code' => 'Company Code',
				'bisnis_area' => 'Bisnis Area',
				'sub_location' => 'Sub Location',
				'date_add'=>'Scan Date',
				'add_by' => 'Scan By'
		);		
		$this->m_master->generate_export($to,$filename,$sql,$title,$column_header);	
	}
	function export_master($to){
		$where = array('B.date_add !='=>null);//complete opname
		$sql = $this->m_master->export_stocktake_master($where);
		$filename = 'opname_complete('.date('d-m-Y',strtotime($this->datenow)).')';
		$title = "OPNAME COMPLETE";		
		$this->m_master->generate_export($to,$filename,$sql,$title,'');
	}
}
