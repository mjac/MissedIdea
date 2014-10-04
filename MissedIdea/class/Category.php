<?php

namespace MissedIdea;

class Category {
	
	protected $_dbh;
	
	public function __construct(){
		$this->_dbh = ConnectDatabase::getInstance();
	}
	
	public function getCategoriesRaw(){
		$sql = "SELECT id, category_name FROM categories";
		$categories = $this->_dbh->query($sql);
		
		$catArray = array();
		foreach($categories as $category){
			$catArray[$category['category_name']] = $category['id'];
		}
		return $catArray;
	}

	//return an associative array and not JSON
	public function getCategories() {
		return json_encode(array('idea' => $this->getCategoriesRaw()));
	}

}

//no tag 
