<?php 

namespace MissedIdea;

class ConnectDatabase {

	//declare instance 
	private static $instance = NULL;
	
	//set the constructor to private so no-one can create an instance using new 
	private function __construct() {
	}
	
	//return connect_database instance or create initial connection and return instance 
	public static function getInstance() {
	
		if (!self::$instance) {
			self::$instance = new \PDO("mysql:host=205.178.146.108;dbname=missedidea", 'jakehoward', 'Counter99');
			self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		}
	return self::$instance;
	}
	
	//make clone private so that no one can clone the instance 
	private function __clone(){
	}
	
}//end of connect_database

//no tag 