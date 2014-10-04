<?php 

namespace MissedIdea;

class CommentManager {

	protected $_dbh;
	
	public function __construct(){
		$this->_dbh = ConnectDatabase::getInstance();
	}
	
	public function getComments($ideaId, $orderBy="date_posted", $orderType="DESC", $limit="500000") {	 
		$commentsResult = $this->_dbh->query("SELECT idea_id, comment_text, date_posted, name FROM comments 
								INNER JOIN user WHERE comments.user_id = user.user_id AND comments.idea_id = $ideaId 
								ORDER BY ".$orderBy." ".$orderType." LIMIT ".$limit);
		return $commentsResult;
	}
	
	 
	public function insertComment($userId, $ideaId, $commentText) {

		$stmt = $this->_dbh->prepare("INSERT INTO comments VALUES (NULL, :userId, :ideaId, :commentText, NOW(), 0)");
		$stmt->bindParam(':userId', $userId); 
		$stmt->bindParam(':ideaId', $ideaId);
		$stmt->bindParam(':commentText', $commentText);
		$count = $stmt->execute();
		
		//increment the number of comments for the idea in idea table 
		$sql = "UPDATE idea SET num_comments = num_comments + 1 WHERE idea_id = $ideaId LIMIT 1";
		$this->_dbh->exec($sql);
		
		//return the number of rows affected for the comment insertion   
		return $count;
	}
	
	function printArray($comments){

		$commentsArray = array(
			'comments' => array(),
			'submitUrl' => 'index.php?module=comment&action=add',
		);

		foreach($comments as $comment){
			$commentsArray['comments'][] = array(
					'name' => $comment['name'],
					'text' => $comment['comment_text'],
					'date' => $comment['date_posted'],
			);
		}
		return $commentsArray;
	}
	
	function printJson($comments) {
		echo json_encode($this->printArray($comments));
	}
	
}//end of comment_manager 

//no tag 
