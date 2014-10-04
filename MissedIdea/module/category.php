<?php

namespace MissedIdea\Module;

class category extends \MissedIdea\JsonRequest {
	public function __construct() {
		parent::__construct();
		$this->ideaManager = new \MissedIdea\IdeaManager();
	}

	public function index() {
		$ideas = $this->ideaManager->getIdeas();
		$this->sendIdeas($ideas);
	}

	public function get($id = 0) {
		$ideas = $this->ideaManager->getIdeas($id);
		$this->sendIdeas($ideas, $id);
	}

	protected function sendIdeas($ideas, $category = NULL) {
		$this->sendResponse(array(
			'category' => $category,
			'ideas' => $this->ideaManager->printArray($ideas),
		));
	}

	public function insert($idea, $id = 0) {
		$ideaManager = new \MissedIdea\IdeaManager();
		$ideaId = $ideaManager->insertIdea(1, $idea, $id);
	
		$ideaManager = new \MissedIdea\IdeaManager();
		$ideaData = $ideaManager->getIdea($ideaId);
		$this->sendResponse($ideaData);
	}
}
