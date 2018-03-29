<?php

define('APIUSERID','winmall001');
define('APIPASSWORD','Kc$xuZL#<)LxSSk[$(TM_CWtm');
define('APISECREATKEY','Jkp:DE*(TEGguuOL9yC2#poOP');

class Wmallrequest{
	
	private $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	public function request($url,$data = '{}'){

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