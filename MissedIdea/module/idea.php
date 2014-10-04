<?php

namespace MissedIdea\Module;

class idea extends \MissedIdea\JsonRequest {
	private $ideaId;

	public function __construct($id) {
		parent::__construct();
		$this->ideaId = $id;
	}

	public function vote() {
		$voteManager = new \MissedIdea\VoteManager($this->ideaId, 1);
		$voteManager->vote();

		$ideaManager = new \MissedIdea\IdeaManager();
		$ideaData = $ideaManager->getIdea($this->ideaId);
		$this->sendResponse($ideaData);
	}
}
