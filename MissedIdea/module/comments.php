<?php

namespace MissedIdea\Module;

class comments extends \MissedIdea\JsonRequest {
	private $ideaId;

	public function __construct($id) {
		parent::__construct();
		$this->ideaId = $id;
	}

	public function get() {
		$commentManager = new \MissedIdea\CommentManager;
		$comments = $commentManager->getComments($this->ideaId);
		$this->sendResponse($commentManager->printArray($comments));
	}

	public function add($commentText) {
		$commentManager = new \MissedIdea\CommentManager;
		$numCommentsCreated = $commentManager->insertComment(1, $this->ideaId, $commentText);

		if ($numCommentsCreated > 0) {
			$this->get();
		}
	}
}
