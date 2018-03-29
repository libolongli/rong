<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends Basecontroller {

	public function __construct(){
		parent::__construct();
		$this->load->library('Wmallrequest');
	}

	/**
	 *  login api
	 *  username/email
	 *  password 
	 */
	public function login(){
		$data = $this->getApiParams();
		print_r($data);exit;
	}

	/**
	 * [notifiction]
	 * @return [type] [description]
	 */
	public function notifiction(){

	}


	/**
	 * [voucher description]
	 * @return [type] [description]
	 */
	public function voucher(){

	}

	/**
	 * [transaction description]
	 * @return [type] [description]
	 */
	public function transaction(){

	}

	/**
	 * [check_point description]
	 * @return [type] [description]
	 */
	public function check_point(){
		
	}

	public function deposit_point(){

	}

	/**
	 * [confirm_transaction paied /unpaied]
	 * @return [type] [description]
	 */
	public function confirm_transaction(){

	}

	public function get_merchantinfo(){
		$data = $this->getApiParams();
		$mbid = $data['mbid'];
		$request = array(
					'mbid'=>$mbid,
					'idtype'=>'mbid'
				);
		$response = $this->wmallrequest->request('http://rebate.winmall.asia/cart_api/get_merchant_info/',json_encode($request));
		if($response['get_merchant_info']['0']){
			$info = $response['get_merchant_info']['0'];
			$result = array(
						'status'=>true,
						'msg'=>'success',
						'code'=>1,
						'result'=>array('mbid'=>$mbid,'storename'=>$info['membername'])
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

	public function get_spoint(){
		$data = $this->getApiParams();
		$mbid = $data['mbid'];
		$request = array(
					'mbid'=>$mbid,
				);
		$response = $this->wmallrequest->request('http://rebate.winmall.asia/cart_api/get_spoint/',json_encode($request));
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

	public function add_spoint(){
		$data = $this->getApiParams();
		$mbid = $data['mbid'];
		$spoint = $data['spoint'];
		$remark = $data['remark'];
		$transactionid = date('YmdHis').rand(10000,99999);
		// {"mbid": "0604148724","transactionid": "0000001"," merchantid": "0604148724","spoint": "10","remark": "just a test","idtype": "mbid"}
		$request = array(
					'mbid'=>$mbid,
					'transactionid'=>$transactionid,
					'merchantid'=>"0604148724",
					'spoint'=>$spoint,
					'remark'=>$remark,
					'idtype'=>'mbid'
				);
		$response = $this->wmallrequest->request('http://rebate.winmall.asia/cart_api/add_spoint_trans/',json_encode($request));
		
		if($response['add_spoint_trans']['0']){
			$info = $response['add_spoint_trans']['0'];
			$result = array(
						'status'=>true,
						'msg'=>'success',
						'code'=>1,
						'result'=>array('memberid'=>$info['memberid'],'spoint'=>$info['spoint'])
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
