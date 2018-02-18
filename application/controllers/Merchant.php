<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant extends Basecontroller {

	/**
	 *  login api
	 *  username/email
	 *  password 
	 */
	public function login(){
		echo 1111;exit;
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
}
