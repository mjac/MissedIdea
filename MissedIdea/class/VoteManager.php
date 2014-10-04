<?php 
/*
* If the user has NOT voted then add one vote to the idea and add the user and idea ID to the voting table 
* If the user has already voted then remove 1 vote from the idea 
* Need to know if the user has voted on the idea so return this information with the idea text
* This class could use some work  
* May look to change the return type of this class 
*/


namespace MissedIdea; 

class VoteManager{
	
	private $_userId;
	private $_ideaId;
	protected $_dbh;
	
	public function __construct($ideaId, $userId){
		$this->_userId = $userId;
		$this->_ideaId = $ideaId;
		$this->_dbh = ConnectDatabase::getInstance();
	}
	
	public function vote(){
		$sql = "SELECT idea_id, user_id FROM vote_check WHERE idea_id = $this->_ideaId AND user_id = $this->_userId";
		$countSql = "SELECT COUNT(*) FROM vote_check WHERE idea_id = $this->_ideaId AND user_id = $this->_userId";
		$voteResults = $this->_dbh->query($sql);
		$numRows = (int) $this->_dbh->query($countSql)->fetchColumn();
		
		if($numRows === 0) {
		
			$updateSql = "UPDATE idea SET votes = votes + 1, date_posted = date_posted WHERE idea_id = $this->_ideaId LIMIT 1";
			$checkSql = "INSERT INTO vote_check (idea_id, user_id) VALUES ($this->_ideaId, $this->_userId)";			
			$this->_dbh->exec($updateSql);
			$this->_dbh->exec($checkSql);
			//return the string up to the client as indication of action taken 
			return "up";
			
		} else {
			//put an if statement here that only reduces the votes if it is greater than 0 do it in SQL if possible.		
			$updateSql = "UPDATE idea SET votes = votes - 1, date_posted = date_posted WHERE idea_id = $this->_ideaId LIMIT 1";
			$checkSql = "DELETE FROM vote_check WHERE idea_id = $this->_ideaId AND user_id = $this->_userId LIMIT 1";
			$this->_dbh->exec($updateSql);		
			$this->_dbh->exec($checkSql);
			//return the string up to the client as indication of action taken 
			return "down";
			
		}
	}
	
	//check to see if the user has already voted on an idea 
	public function checkVote(){
		$sql = "SELECT idea_id, user_id FROM vote_check WHERE idea_id = $this->_ideaId AND user_id = $this->_userId";
		$countSql = "SELECT COUNT(*) FROM vote_check WHERE idea_id = $this->_ideaId AND user_id = $this->_userId";
		$voteResults = $this->_dbh->query($sql);
		$numRows = $this->_dbh->query($countSql)->fetchColumn();
		if($numRows > 0){
			return "voted";
		}elseif($numRows === 0){
			return "notVoted";
		}else{
			throw new Exception("Error in checkVote() function in VoteManager class");  
		}	
	}

}
	
//no tag 
