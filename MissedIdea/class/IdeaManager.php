<?php 

namespace MissedIdea;

class IdeaManager { 
	
	protected $_dbh;
	
	public function __construct(){
		$this->_dbh = ConnectDatabase::getInstance();
	}

	public function getIdea($ideaId) {
		$dbResult = $this->_dbh->query("SELECT idea.idea_id, name, idea_text, date_posted, votes, num_comments, NOT(vote_check.idea_id IS NULL) AS hasVoted FROM user INNER JOIN idea ON user.user_id = idea.user_id LEFT JOIN vote_check ON vote_check.user_id = idea.user_id AND vote_check.idea_id = idea.idea_id WHERE idea.idea_id = $ideaId");
		$ideaArray = $this->printArray($dbResult);
		return $ideaArray[0];
	}

	//gets ideas from database and returns array 
	public function getIdeas($categoryId = "all", $orderBy = "date_posted", $descending = TRUE, $limit="50000") {
		$orderType = $descending ? "DESC" : "ASC";

		return $this->_dbh->query("SELECT idea.idea_id, name, idea_text, date_posted, votes, num_comments, NOT(vote_check.idea_id IS NULL) AS hasVoted FROM user INNER JOIN idea ON user.user_id = idea.user_id LEFT JOIN vote_check ON vote_check.user_id = idea.user_id AND vote_check.idea_id = idea.idea_id "
			. ($categoryId === 'all' ? '' : "WHERE category_id = $categoryId ")
			. "ORDER BY $orderBy $orderType LIMIT $limit");
	}
	
	//inserts ideas into database
	public function insertIdea($userId, $ideaText, $categoryId) {
		$stmt = $this->_dbh->prepare("INSERT INTO idea VALUES (NULL, :userId, :ideaText, NOW(), 0, 0, :categoryId, 0, 0)");

		$stmt->bindParam(':userId', $userId); 
		$stmt->bindParam(':ideaText', $ideaText);
		$stmt->bindParam(':categoryId', $categoryId);

		$insertCount = $stmt->execute();

		if ($insertCount > 0) {
			return $this->_dbh->lastInsertId();
		} else {
			return NULL;
		}
	}

	public function printArray($ideas) {
		$ideasArray = array();

		foreach($ideas as $idea){
			$ideasArray[] = array(
				'name' => $idea['name'],
				'text' => $idea['idea_text'],
				'date' => $idea['date_posted'],
				'votes' => $idea['votes'],
				'numComments' => $idea['num_comments'],
				'hasVoted' => $idea['hasVoted'] === '1',
				'commentsLink' => 'index.php?module=comments&action=get&id=' . $idea['idea_id'],
				'voteLink' => 'index.php?module=idea&action=vote&id=' . $idea['idea_id'],
				'submitCommentUrl' => 'index.php?module=comments&action=add&id=' . $idea['idea_id'],
			);
		}

		return $ideasArray;	
	}
	
	//use the JSON request class? 
	public function printJson($ideas){
		echo json_encode(array('ideas' => $this->printArray($ideas)));
	}

} //end of idea_manager 

//no tag 
