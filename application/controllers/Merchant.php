<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends Basecontroller {

	public function __construct(){
		parent::__construct();
		$this->load->library('Wmallrequest');
	}

	
	/**
	 * [confirm_transaction paied /unpaied]
	 * @return [type] [description]
	 */
	public function confirm_transaction(){

		//update order status
		$data = $this->getApiParams();
		$transactionid = $data['transaction_id'];
		$this->load->model('transaction_m');
		$this->load->model('notification_m');
		$this->load->model('member_m');
		$trans = $this->transaction_m->selectByCons(array('transaction_id'=>$transactionid));
		if($trans['status'] == 1){
			$result = array('status'=>false,'msg'=>'already confirmed','code'=>'009');
			$this->teamapi($result);			
		}

		$this->transaction_m->trans_begin();
		// $this->transaction_m->trans_commit();
		// $this->transaction_m->trans_rollback();
		$this->transaction_m->update(array('transaction_id'=>$transactionid),array('status'=>1));
		//request rbs to add point
		$order_id = date('YmdHis'). $trans['member_id'];
		$request = array(
					'mbid'=>$trans['member_id'],
					'transactionid'=>$order_id,
					// 'merchantid'=>'0604148724',
					'merchantid'=>$trans['merchant_id'],
					'spoint'=>$trans['total'],
					'remark'=>$transactionid,
					'idtype'=>'mbid'
				);
		
		$response = $this->wmallrequest->add_spoint_trans(json_encode($request));

		if($response['add_spoint_trans']['0']){
			$result = array(
						'status'=>true,
						'msg'=>'success',
						'code'=>0,
				);
			$merchant_info = $this->member_m->selectByCons(array('mbid'=>$trans['merchant_id']));
			$notification_data = array(
				'title'=>'already paid',
				'content'=>$merchant_info['name'].' confirmed your pay, RM'.$trans['total'],
				'mbid'=>$trans['member_id'],
				'create_timestamp'=>date('Y-m-d H:i:s'),
				'transaction_id'=>$transactionid
			);

			$this->notification_m->insert($notification_data);
			$this->transaction_m->trans_commit();
		}else{
			$result = array(
						'status'=>false,
						'msg'=>$response['Message'],
						'code'=>$response['Code'],
				);
			$this->transaction_m->trans_rollback();
		}


		$this->teamapi($result);

	}

	public function get_merchantinfo(){
		
		$data = $this->getApiParams();
		$mbid = $data['mbid'];
		$request = array(
					'mbid'=>$mbid,
					'idtype'=>'mbid'
				);
		$response = $this->wmallrequest->get_merchant_info(json_encode($request));
		if($response['get_merchant_info']['0']){
			$info = $response['get_merchant_info']['0'];
			$result = array(
						'status'=>true,
						'msg'=>'success',
						'code'=>1,
						'result'=>array('merchantid'=>$mbid,'storename'=>$info['membername'])
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
