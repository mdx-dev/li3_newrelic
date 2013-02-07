<?php

namespace li3_newrelic\config;

use lithium\core\Libraries;
use lithium\action\Dispatcher;
use lithium\core\Environment;
use li3_newrelic\extensions\Newrelic;

/**
 * Main filter to add newrelic transaction for each request.
 */
Dispatcher::applyFilter('_callable', function($self, $params, $chain) {

	$controller = $chain->next($self, $params, $chain);

	Newrelic::filterDispatcher($controller, $params);

	return $controller;
});

?>
