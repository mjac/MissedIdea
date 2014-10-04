<?php

namespace MissedIdea;

require 'Reflection-Router/ReflectionRouter.php';

spl_autoload_register(function($className) {
	if (strpos($className, 'Mustache') === 0) {
		include dirname(__FILE__) . DIRECTORY_SEPARATOR 
			. 'mustache.php' . DIRECTORY_SEPARATOR
			. $className . '.php';
		return;
	}elseif(strpos($className, 'Facebook') === 0) {
		include dirname(__FILE__) . DIRECTORY_SEPARATOR 
			. 'fb-php-sdk' . DIRECTORY_SEPARATOR
			. DIRECTORY_SEPARATOR . src 
			. DIRECTORY_SEPARATOR . 'facebook' . '.php';
		return;
	}

	$namespace = __NAMESPACE__ . '\\';
	if (strpos($className, $namespace) !== 0) {
		return;
	}

	$missedIdeaClass = substr($className, strlen($namespace));
	$modulesNamespace = 'Module\\';

	if (strpos($missedIdeaClass, $modulesNamespace) === 0) {
		$moduleClass = substr($missedIdeaClass, strlen($modulesNamespace));
		$moduleFile = dirname(__FILE__) . DIRECTORY_SEPARATOR
			. 'module' . DIRECTORY_SEPARATOR 
			. $moduleClass . '.php';

		if (file_exists($moduleFile)) {
			include $moduleFile;
		}

		return;
	}

	include dirname(__FILE__) . DIRECTORY_SEPARATOR
		. 'class' . DIRECTORY_SEPARATOR 
		. $missedIdeaClass . '.php';
});