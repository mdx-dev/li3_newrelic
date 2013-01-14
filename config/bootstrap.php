<?php

use lithium\core\Libraries;
use lithium\action\Dispatcher;
use lithium\core\Environment;

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {

	$controller = $chain->next($self, $params, $chain);

	if (Environment::is('production') && extension_loaded('newrelic')) {
		$ctrl = explode('\\', get_class($controller));
		$controllerName = preg_replace('/Controller$/', '', array_pop($ctrl));
		newrelic_name_transaction(strtolower($controllerName) . '/' . $params['request']->params['action']);
	}

	return $controller;
});

?>