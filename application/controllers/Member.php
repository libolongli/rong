<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Member extends Basecontroller {

	public function __construct(){
		parent::__construct();
		$this->load->library('Wmallrequest');
	}

	/**
	 *  login api
	 *  username/email
	 *  password 
	 *  check the user imformation from the rbs system and store them in the db
	 */
	public function login(){
		$data = $this->getApiParams();
		$username = trim($data['username']);
		$password = trim($data['password']);
		if($username && $password){
			$request = array('email'=>$username,'password'=>$password);
			$response = $this->wmallrequest->request('http://rebate.winmall.asia/cart_api/login/',json_encode($request));
			//证明登陆成功
			if(isset($response['login'])){
				$detail = $response['login']['0'];
				//insert into db
				$db_data = array(
						'member_id'=>intval($detail['memberid']),
						'name'=>$detail['name'],
						'gender'=>$detail['gender'],
						'address1'=>$detail['address'],
						'zipcode'=>$detail['postcode'],
						'city'=>$detail['city'],
						'state'=>$detail['state'],
						'country'=>$detail['country'],
						'phone'=>$detail['mobiletel'],
						'email'=>$detail['email'],
						'ismerchant'=>$detail['ismerchant']

					);
				$this->load->model('member_m');
				$this->member_m->login($db_data);
				header("Location: /member/dashboard");
			}
		}
	}

	/**
	 * [dashboard member dashboard]
	 * @return [type] [description]
	 */
	public function dashboard(){
		echo 'dashboard';
		$this->load->model('member_m');
		$data = $this->member_m->login_info();
		print_r($data);
		exit;
	}

	/**
	 * [notifiction]
	 * @return [type] [description]
	 */
	public function notifiction(){

	}

	/**
	 * [payment description]
	 * if > 100,should notice the rbs
	 *  deduct merchant point
	 * @return [type] [description]
	 */
	public function payment(){

	}

	/**
	 * [voucher description]
	 * @return [type] [description]
	 */
	public function voucher(){

	}

	/**
	 * [trasnaction description]
	 * paied unpaied 
	 * @return [type] [description]
	 */
	public function trasnaction(){

	}

	
}
