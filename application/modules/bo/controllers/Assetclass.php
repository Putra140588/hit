<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class Assetclass extends  CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->datenow = $_SESSION['date_now'];
		$this->addby = $this->session->userdata('nama_depan');
		$this->class = strtolower(__CLASS__);
		$this->acces_code = 'ASCLS';
		$this->table = 'tb_asset_class';
		
	}
	var $datenow;
	var $addby;
	var $class;
	var $acces_code;
	var $table;
	function index(){	
		$this->m_master->get_login();
		$priv = $this->m_master->get_priv($this->acces_code,'view');
		$main_page = (empty($priv)) ? 'bo/asset_class/v_index_asset_class' : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];
		
		$data['page_title'] = "Asset Class";
		$data['class'] = $this->class;
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function column(){
		//indeks nilai array ke nama column table
		$column_array = array(
				0 => 'asset_class',//default order sort
				1 => 'asset_class',
				2 => 'nama_class',				
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
		$total = count($this->m_master->get_table_filter($this->table));
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
		$query = $this->m_master->get_table_filter($this->table,'',$this->column()[$request['column']],$request['sorting'],$request['length'],$request['start']);
	
		/*Ketika dalam mode pencarian, berarti kita harus
		 'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($request['keyword'] !=""){
			$this->m_master->search_like($request['keyword'],$this->column());
			$total = count($this->m_master->get_table_filter($this->table));
			/*total record yg difilter*/
			$output['recordsFiltered'] = $total;
		}
		$nomor_urut=$request['start']+1;
		foreach ($query as $row) {
			//show in html
			$output['data'][]=array($nomor_urut,
					$row->asset_class,
					$row->nama_class,					
					'<button title="'.$this->config->config['delete'].'" type="button" id="delete" class="btn btn-danger btn-circle" onclick="ajaxDelete(\''.base_url('bo/'.$this->class.'/delete').'\',\''.$row->asset_class.'\',\'tes\')"><i class="icon-trash"></i></button>
					 <a href="'.base_url('bo/'.$this->class.'/form/'.$row->asset_class).'" title="'.$this->config->config['edit'].'" class="btn btn-info btn-circle"><i class="icon-edit"></i></a>'
			);
			$nomor_urut++;
		}
		echo json_encode($output);	
	}
	function form($id='',$detail=''){
		$this->m_master->get_login();
		$view = 'bo/asset_class/v_crud_asset_class';
		if ($id !=''){
			//akses edit
			$action = 'edit';
			$data['page_title'] = "Ubah Asset Class";
			$sql = $this->m_master->get_table_filter($this->table,array('asset_class'=>$id));
			foreach ($sql as $row)
				foreach ($row as $key=>$val){
				$data[$key] = $val;
			}
			
		}else{
			//akses tambah
			$action = 'add';
			$data['page_title'] = "Tambah Asset Class";
		}
		$priv = $this->m_master->get_priv($this->acces_code,$action);
		$main_page = (empty($priv)) ? $view : 'bo/'.$priv['error'];
		$data['notif'] = (empty($priv)) ? '' : $priv['notif'];	
		$data['class'] = $this->class;		
		$this->load->view('bo/v_header',$data);
		$this->load->view($main_page);
		$this->load->view('bo/v_footer');
	}
	function proses(){
		$post = $this->input->post();
		$id= $post['id'];//for update		
		$put['nama_class'] = $post['nama'];		
		if ($id != ''){
			//edit proses
			$res = $this->m_master->updatedata($this->table,$put,array('asset_class'=>$id));
			if ($res > 0){
				$this->session->set_flashdata('success','Ubah asset class berhasil.');
				redirect('bo/'.$this->class);
			}
		}else{			
			$put['asset_class'] = $post['assetclass'];
			$res = $this->m_master->insertdata($this->table,$put);
			if ($res > 0){
				$this->session->set_flashdata('success','Tambah asset class berhasil.');
				redirect('bo/'.$this->class);
			}
		}
	}
	function delete(){
		$priv = $this->m_master->get_priv($this->acces_code,'delete');
		if (empty($priv)){
			$val = $this->input->post('value');
			$res = $this->m_master->deletedata($this->table,array('asset_class'=>$val));
			if ($res){
				echo json_encode(array('error'=>0,'msg'=>'Hapus asset class berhasil'));
			}
		}else{
			echo json_encode(array('error'=>1,'msg'=>$priv['notif']));
		}
	}
}