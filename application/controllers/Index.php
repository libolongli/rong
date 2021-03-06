<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends Basecontroller {

	/**
	 * [qrcode description]
	 * @return [type] [description]
	 */
	public function qrcode(){
		echo __FUNCTION__;
	}

	/**
	 * [get_member_token member_id]
	 * mid 
	 * .....
	 * @return [type] [description]
	 */
	public function get_member_token(){
		
		$detail = $this->getApiParams();
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
		$this->member_m->replace($db_data);
		
		$redis_key = md5($db_data['mbid']);
		$this->wredis->setex($redis_key,60,$db_data['mbid']);

		$this->teamapi(array('status'=>true,'code'=>0,'result'=>array('token'=>$redis_key)));
	}

	/**
	 * [fastlogin description]
	 * @return [type] [description]
	 */
	public function fastlogin(){
		$token = $this->input->get('token');
		$mbid = $this->input->get('mid');
		if($this->wredis->get($token) == $mbid){
			$this->wredis->del($token);
			$this->load->model('member_m');
			$logindata = $this->member_m->selectByCons(array('mbid'=>$mbid));
			$this->member_m->login($logindata);
			header("Location: /member/notifiction");
		}else{
			$this->teamapi(array('status'=>false,'code'=>1,'msg'=>'token is illegal！'));
		}
		
	}

	public function qr(){
		$detail = $this->getApiParams();
		$mbid=$detail['memberid'];
		$this->load->library('Qrcode');
		$this->qrcode->merchantcode($mbid);	
		$this->teamapi(array('status'=>true,'code'=>0,'result'=>array('url'=>'http://www.favoko.com/app/qrcode/'.$mbid.'.png')));
	}


}

