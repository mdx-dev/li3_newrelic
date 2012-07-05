<?php

use lithium\core\Libraries;
use lithium\action\Dispatcher;
use lithium\core\Environment;

Dispatcher::applyFilter('_call', function($self, $params, $chain) {

	if (Environment::is('production') && extension_loaded ('newrelic')) {
		newrelic_name_transaction ($params['request']->params['controller'].'/'.$params['request']->params['action']);
	}
	
	return $chain->next($self, $params, $chain);
});

?>