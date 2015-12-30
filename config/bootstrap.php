<?php

use lithium\core\Libraries;
use lithium\action\Dispatcher;
use lithium\core\Environment;

Dispatcher::applyFilter('_callable', function($self, $params, $chain) {

	$controller = $chain->next($self, $params, $chain);

	if (extension_loaded('newrelic')) {
		$controllerName = preg_replace('/Controller$/', '', array_pop(explode('\\', get_class($controller))));
		newrelic_name_transaction ($controllerName.'/'.$params['request']->params['action']);
	}

	return $controller;
});

?>
