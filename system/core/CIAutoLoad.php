<?php
spl_autoload_register ( array (
		'CIAutoLoad',
		'loadclass' 
) );
abstract class CIAutoLoad {
	private static $load_classes = array ();
	static final function loadClass($className = NULL) {
		$strict_loads = array (
				"base_model",
				"base_platform",
		);
		if (in_array ( strtolower ( $className ), $strict_loads )) {
			return self::import ( APPPATH . 'models/' . strtolower ( $className ) . '.php' );
		}
		return self::import ( APPPATH . 'controllers/' . strtolower ( $className ) . '.php' );
	}
	static final function import($filePath) {
		if (file_exists ( $filePath )) {
			return include_once $filePath;
		}
	}
}