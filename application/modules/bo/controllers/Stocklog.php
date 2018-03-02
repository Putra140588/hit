<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Stocklog extends  CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'STLOG';
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
		$main_page = (empty($priv)) ? 'bo/stock/v_index_stocklog' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Stock Opname Log";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'A.date_add',//default order sort
				1 => 'A.product_code',
				2 => 'B.description',
				3 => 'B.asset_class',
				4 => 'C.nama_class',
				5 => 'B.company_code',
				6 => 'B.bisnis_area',
				7 => 'B.sub_location',
				8 => 'A.date_add',
				9 => 'A.add_by',
			   10 => 'B.deleted',
								
		);
		return $column_array;
	}
	function get_records(){
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
		$total = count($this->m_master->get_stock_log());
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
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
		}
		/*Pencarian ke database*/
		$query = $this->m_master->get_stock_log('',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_stock_log());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] == ""){
			$this->db->like('A.date_add',$request['date_from']);
			$total = count($this->m_master->get_stock_log());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}elseif ($request['date_from'] != "" && $request['date_to'] != ""){
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") >=',$request['date_from']);
			$this->db->where('DATE_FORMAT(A.date_add,"%Y-%m-%d") <=',$request['date_to']);
			$total = count($this->m_master->get_stock_log());
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
	
	
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			if (isset($row->deleted)){
				$deleted = ($row->deleted == 1) ? '<span class="label label-success">Found</span>' : '<span class="label label-danger">Deleted</span>';
			}else{
				$deleted = '<span class="label label-warning">Not Found</span>';
			}
			//show in html
			$output['data'][]=array($nomor_urut,
					$row->product_code,											
					$row->description,
					$row->asset_class,
					$row->nama_class,
					$row->company_code,
					$row->bisnis_area,
					$row->sub_location,
					$row->date_add,
					$row->add_by,	
					$deleted,				
					'<button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->id_stock_take.'\',\'tes\')"><i class="icon-trash"></i></button>'												 
			);
			$nomor_urut++;
		}
		echo json_encode($output);
	
	}
	
	function delete(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->deletedata($this->table,array('id_stock_take'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus Item Code '.$val.' berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	}
	function delete_all(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			//jika login selain superadmin dan admin
			if ($this->m_master->group_akses() == false){
				$res = $this->m_master->deletedata($this->table,array('id_user'=>$this->id_user));
			}else{
				$res = $this->m_master->deletedata($this->table);
			}		
			if ($res){
				$this->session->set_flashdata('success','Hapus seluruh data stok opname berhasil');
				redirect('bo/'.$this->class);
			}
		}else{
			$this->session->set_flashdata('danger',$priv['notif']);
			redirect('bo/'.$this->class);
		}
	}
	function export($to){		
		$sql = $this->m_master->export_stock_log();
		$filename = 'stock_opname_log('.date('d-m-Y',strtotime($this->datenow)).')';
		$title = "STOCK OPNAME LOG";
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
}
