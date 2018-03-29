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
		$email = trim($data['email']);
		$password = trim($data['password']);
		if($email && $password){
			$request = array('email'=>$email,'password'=>$password);
			$response = $this->wmallrequest->login(json_encode($request));
			//证明登陆成功
			if(isset($response['login'])){
				$detail = $response['login']['0'];
				//insert into db
				$db_data = array(
						'mbid'=>$detail['memberid'],
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
				$result = array(
						'status'=>true,
						'msg'=>'success',
						'code'=>0,
						'result'=>$db_data
				);
				$this->teamapi($result);
			}else{
				$result = array(
						'status'=>false,
						'msg'=>$response['Message'],
						'code'=>$response['Code'],
				);
				$this->teamapi($result);
			}
		}else{

			$result = array(
						'status'=>false,
						'msg'=>'email / password',
						'code'=>-1,
				);
			$this->teamapi($result);
		}
	}

	/**
	 * [dashboard member dashboard]
	 * @return [type] [description]
	 */
	// public function dashboard(){
	// 	echo 'dashboard';
	// 	$this->load->model('member_m');
	// 	$data = $this->member_m->login_info();
	// 	print_r($data);
	// 	exit;
	// }

	/**
	 * [notifiction]
	 * @return [type] [description]
	 */
	public function notifiction(){
		$this->load->model('member_m');
		$info = $this->member_m->login_info();
		$sql = "SELECT n.title,n.content,n.create_timestamp,t.member_id,m.`name`,t.total,t.voucher,t.cash,n.transaction_id FROM notification as n 
							LEFT JOIN transaction as t on n.transaction_id = t.transaction_id 
							LEFT JOIN member as m on t.member_id = m.mbid where n.mbid = '{$info['mbid']}'";
		$data = $this->member_m->querySql($sql);
		$result = array(
				'status'=>true,
				'msg'=>'success',
				'code'=>0,
				'result'=>$data
			);
		$this->teamapi($result);
	}

	/**
	 * [payment description]
	 * if > 100,should notice the rbs
	 *  deduct merchant point
	 * @return [type] [description]
	 */
	public function payment(){
		$data = $this->getApiParams();
		//{"merchantid":"8984282023","total":25.00,"e-voucher":20,"cash":5.00}
		$this->load->model('transaction_m');
		$this->load->model('member_m');
		$this->load->model('notification_m');

		$info = $this->member_m->login_info();
		$db_data = array(
				'cash'=>$data['cash'],
				'voucher'=>$data['e-voucher'],
				'total'=>$data['cash'] + $data['e-voucher'],
				'member_id'=>$info['mbid'],
				'merchant_id'=>$data['merchantid'],
				'create_timestamp'=>date('Y-m-d H:i:s'),
				'status'=>0,
			);

		$db_data['trans_no'] = $this->transaction_m->getTradeNo($info['mbid']);
		$transaction_id = $this->transaction_m->insert($db_data);

		//if used voucher shuold - voucher

		//inesrt notifictions
		$notification_data = array(
				'title'=>'user pay',
				'content'=>$info['name'].' paid RM'.$db_data['total'],
				'mbid'=>$data['merchantid'],
				'create_timestamp'=>date('Y-m-d H:i:s'),
				'transaction_id'=>$transaction_id
			);
		$this->notification_m->insert($notification_data);
		$result = array(
						'status'=>true,
						'msg'=>'success',
						'code'=>0,
				);
		$this->teamapi($result);

	}

	/**
	 * [voucher description]
	 * @return [type] [description]
	 */
	// public function voucher(){

	// }

	public function get_spoint(){
		$data = $this->getApiParams();
		$mbid = $data['mbid'];
		$request = array(
					'mbid'=>$mbid,
				);
		$response = $this->wmallrequest->get_spoint(json_encode($request));
		if($response['get_spoint']['0']){
			$info = $response['get_spoint']['0'];
			$result = array(
						'status'=>true,
						'msg'=>'success',
						'code'=>1,
						'result'=>array('mbid'=>$mbid,'spoint'=>$info['spoint'])
				);
		}else{
			$result = array(
						'status'=>false,
						'msg'=>'faild',
						'code'=>0,
				);
		}
		$this->teamapi($result);
	}
	


}
