<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BaseController extends CI_Controller {
    

	public function __construct(){
		parent::__construct();
		
	}

	/**
	 * [checklogin 检查是否已经登录]
	 * @return [type] [description]
	 */
	public function checklogin(){
		
		
	}


	/*包装前台需要返回的数据
	 * {Success: false, Code: "0.2", Message: "密码错误，您还可以输入4次！", Result: [{TotalErrorCount: 4, LockMinute: 0}]}
	 * {Success: true, Code: "1.0", Message: "Unknown", Result: [,…]}
	*/
	public function teamapi($data){
		header('Content-Type:application/json; charset=utf-8');
		$response = array();
		$response['Success'] = $data['status'] === true ? true : false; 
		$response['Code']    = isset($data['code']) ? $data['code'] : 'undefined';
		if(isset($data['msg']) && $data['msg']) $response['Message'] = $data['msg'];
		if(isset($data['message']) && $data['message']) $response['Message'] = $data['message'];

		if(isset($data['result'])){
			if(is_array($data['result'])){
			 	$response['Result'] = $data['result'];
			}else{
				$tmp = json_decode($data['result'],TRUE);
				if(is_array($tmp)){
					$response['Result'] = $tmp;
				}else{
					$response['Result'] = array();
				}
			}
		}else{
			$response['Result'] = array();
		}

		exit(json_encode($response));

	}

	public function getApiParams(){
		$data = file_get_contents('php://input'); 
		$data = json_decode($data,TRUE);
		if(!$data){
			$data = $_POST;
		}
		
		return $data;
	}


}
