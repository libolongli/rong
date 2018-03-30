<?php

/**
 * member
 */
class member_m extends Base_Model{
	
	public function __construct() {
		$this->setTableName("member");
		parent::__construct();
	}

	/**
	 * [login description]
	 * @return [type] [description]
	 */
	public function login($db_data){
		$id = $this->replace($db_data);
		$this->session->set_userdata($db_data);
		
		//QRCode
		$mbid = $db_data['mbid'];
		if(!file_exists('qrcode/'.$mbid.'.png') && $db_data['ismerchant'] == 'Y'){
			$this->load->library('Qrcode');
			$this->qrcode->merchantcode($mbid);
		}

	}

	public function login_info(){
		return $this->session->all_userdata();
	}

	public function is_login(){
		$data = $this->login_info();
		if(isset($data['mbid'])){
			return true;
		}

		return false;
	}
}
