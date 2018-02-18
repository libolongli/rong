<?php

/**
 * voucher
 */
class voucher extends Base_Model{
	
	public function __construct() {
		$this->setTableName("voucher");
		parent::__construct();
	}
}
