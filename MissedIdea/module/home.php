<?php

namespace MissedIdea\Module;

class home {
	public function index() {
		$ideaManager = new \MissedIdea\IdeaManager();
		$ideas = $ideaManager->getIdeas();
		$ideaArray = $ideaManager->printArray($ideas);

		$category = new \MissedIdea\Category();
		$categoryList = $category->getCategoriesRaw();
		ksort($categoryList);

		$categoryOutput = array();
		foreach ($categoryList as $name => $id) {
			if ($id > 0) {
				$categoryOutput[] = array(
					'id' => $id,
					'link' => 'index.php?module=category&action=get&id=' . $id,
					'name' => $name,
				);
			}
		}

		$tplLoader = new \MustacheLoader('tpl/');
		$mustache = new \Mustache();

		$sourceTemplates = array('idea', 'comment', 'comments');
		$templates = array();
		foreach ($sourceTemplates as $id) {
			$templates[] = array(
				'id' => $id,
				'content' => $tplLoader[$id],
			);
		}

		echo $mustache->render($tplLoader['index'], array(
			'ideas' => $ideaArray,
			'allLink' => 'index.php?module=category&id=0',
			'submitIdeaUrl' => 'index.php?module=category&action=insert',
			'categoryList' => $categoryOutput, 
			'templates' => $templates,
		), array(
			'ideaTemplate' => $tplLoader['idea'],
		));
	}
}
