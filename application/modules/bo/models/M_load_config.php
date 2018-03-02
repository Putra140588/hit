<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');
class M_load_config extends CI_Model{
	public function __construct(){
		parent::__construct();		
		$_SESSION['date_now'] = date('Y-m-d H:i:s');
	}
}
