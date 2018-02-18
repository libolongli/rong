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
		// $this->session->sess_destroy();
		$this->session->set_userdata($db_data);
	}

	public function login_info(){
		return $this->session->all_userdata();
	}

	public function is_login(){
		$data = $this->login_info();
		if(isset($data['member_id'])){
			return true;
		}

		return false;
	}
}
