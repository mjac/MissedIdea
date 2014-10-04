<?php 
	/*
	* Ranking code to list the ideas in optimal order
	* algorithm from: http://amix.dk/blog/post/19588
	* copy of reddit algorithm except down votes are not considered 
	*/
	
	require 'MissedIdea.php';
	
	$dbh = \MissedIdea\ConnectDatabase::getInstance();
	
	$sql = "SELECT idea_id, date_posted, votes FROM idea";
	
	$ideaData = $dbh->query($sql);
	
	date_default_timezone_set('GMT');
	
	
	
	function epochSeconds($dateArg){
		//returns the number of seconds from the UNIX epoch to dateArg 
		$epoch = mktime(0,0,0,1,1,1970);
		return $dateArg - $epoch;
	}
	
	function score($votes, $timeStamp){
		$order = log10(max($votes,1));
		$sign = ($votes > 0) ? 1 : 0;
		$seconds = epochSeconds($timeStamp) - 1134028003;
		return round($order + $sign * $seconds/45000, 7);
	}
	
	//code to actually update the score field 
	foreach($ideaData as $idea){
		$ideaId = $idea['idea_id'];
		$votes = $idea['votes'];
		$timeStamp = strtotime($idea['date_posted']);
		
		$ideaScore = score($votes, $timeStamp);
		
		$updateSql = "UPDATE idea SET score = $ideaScore WHERE idea_id = $ideaId LIMIT 1";
		$dbh->exec($updateSql);
	}
//notag