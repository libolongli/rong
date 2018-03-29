<?php

/**
 * member
 */
class transaction_m extends Base_Model{
	
	public function __construct() {
		$this->setTableName("transaction");
		parent::__construct();
	}

	
	public function getTradeNo($account_id){
		$dt = new DateTime('NOW');
		$time8 = dechex($dt->format('U'));// 8bit
		$user6 = sprintf("%08s", substr(dechex($account_id), 0,8)); // 8bit
		$fs = explode('.', microtime(true));
		$fsend = end($fs);
		$haomiao4 =sprintf("%04d", $fsend);// 4bit

		return $user6.$time8.$haomiao4;//BM平台 订单号生成规则
	}

}
