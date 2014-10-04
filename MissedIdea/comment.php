<?php 
require 'MissedIdea.php';

	try{
		if(isset($_POST['createComment'])){
		
			$userId = $_POST['userId'];
			$ideaId = $_POST['ideaId'];
			$commentText = $_POST['commentText']; 
			//create an idea manager and add the idea to the database 	
			$commentManager = new \MissedIdea\CommentManager();	
			$commentManager->insertComment($userId, $ideaId, $commentText);
			 
		}
	}catch (PDOException $pdoe){
		
		//log the error in a file? 
		header("Location: http://www.missedidea.com/error.html");
		
	}catch (Exception $e){
		//Same as above
		header("Location: http://www.missedidea.com/error.html");
	}
	
//no tag 