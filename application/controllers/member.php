<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('APIUSERID','winmall001');
define('APIPASSWORD','Kc$xuZL#<)LxSSk[$(TM_CWtm');
define('APISECREATKEY','Jkp:DE*(TEGguuOL9yC2#poOP');

class Member extends Basecontroller {

	public function __construct(){
		parent::__construct();
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
			$response = $this->member_curl('http://rebate.winmall.asia/cart_api/login/',json_encode($request));
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

	/**
	 * [ed encode]
	 * @return [type] [description]
	 */
	// public function ed(){
		
	// 	$data = json_encode(array('email'=>'sianden@hotmail.com','password'=>'kEio2424@B'));
	// 	$response = $this->member_curl('http://rebate.winmall.asia/cart_api/login/',$data);
	// 	print_r($response);exit;
	// }

	/**
	 * [member_curl curl]
	 * @return [type] [description]
	 */
	private function member_curl($url,$data){

		$timestamp = time() - 11*3600;
		$str = APIUSERID.'|==|'.APIPASSWORD.'|==|'.$timestamp;
		$hash = base64_encode(hash_hmac('sha256',$str,APISECREATKEY,TRUE));

		$header = array(
		    "apihash: {$hash}",
		    "apiuserid: ".APIUSERID,
		    "charset: UTF-8",
		    "content-type: application/json",
		    "timestamp: {$timestamp}"
		  );

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $data,
		  CURLOPT_HTTPHEADER => $header
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		return json_decode($response,TRUE);

	}

}
