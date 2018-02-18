<?php
if (! class_exists ( "Redis" )) {
	class Redis {
		function connect() {
		}
		function set() {
		}
		function get() {
		}
	}
}
if (! class_exists ( "Redis" ))
	exit ( 'No php-redis extension, please install it, visit http://pecl.php.net got it!' );
class WRedis extends Redis {
	private $CI;
	public function __construct() {
		$this->CI = &get_instance ();
		$this->CI->load->config ( 'redis' );
		$redis_config = $this->CI->config->item ( 'redis' );
		$host = $redis_config ['host'];
		$port = $redis_config ['port'];
		parent::connect ( $host, $port );
	}
}