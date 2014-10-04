<?php

namespace MissedIdea;

class JsonRequest {
	public function __construct() {
		header('Content-Type: application/json');
	}

	protected function sendResponse($data) {
		echo json_encode($data);
	}	
}
