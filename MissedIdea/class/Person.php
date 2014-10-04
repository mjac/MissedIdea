<?php 

namespace MissedIdea;


class Person {
	private $_name;
	private $_facebookId;
	private $_userId;
	private $_dbh; 	
			
	public function __construct($facebookId){
		$this->_dbh = ConnectDatabase::getInstance();
	
		//take one id as argument (facebook id), and then populate the rest from the database
		$this->_facebookId = $facebookId;
		$userInformation = $this->_dbh->query("SELECT user_id, name, FB_id FROM user 
																	WHERE FB_id = '".$facebookId."' LIMIT 1");
		foreach($userInformation as $userInfo){
			$this->_name = $userInfo['name'];
			$this->_userId = $userInfo['user_id'];
		}
	}
	
	//can't use getName as it is reserved. 
	public function getUserName(){
		return $this->_name;
	}
	
	public function getUserId(){
		return $this->_userId;
	}
	
	public function getFacebookId(){
		return $this->_facebookId;
	}
}

//no tag