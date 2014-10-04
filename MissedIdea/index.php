<?php

require 'MissedIdea.php';

$input = array_merge($_GET, $_POST);
$module = isset($input['module']) ? $input['module'] : 'home';
$action = isset($input['action']) ? $input['action'] : 'index';

try {
	$actionRouter = new \ReflectionRouter\Action('\\MissedIdea\\Module');
	$actionRouter->perform($module, $action, $input);
} catch (\ReflectionRouter\Exception $e) {
	header('Content-Type: text/plain');
	throw new Exception('Ooops, you have specified an invalid module', 0, $e);
}

