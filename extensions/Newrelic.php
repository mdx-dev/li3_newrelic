<?php

namespace li3_newrelic\extensions;

use lithium\core\Environment;
use lithium\core\Libraries;
use lithium\core\StaticObject;

class Newrelic extends StaticObject {

	/**
	 * Simple class to call newrelic method if call standard is met.
	 *
	 * This also provides a simple oo wraper to make newrelic calls appear less
	 * functional. This is the main public API for clients.
	 *
	 * {{{
	 * newrelic_custom_metric(); // Old usage
	 * Newrelic::custom_metric(); // Shiny new usage
	 * }}}
	 *
	 * @link    https://newrelic.com/docs/php/the-php-api
	 * @param   string $method Method called
	 * @param   array  $params Args passed to method
	 * @return  mixed          Function called, or false if `shouldRun` is false
	 */
	public static function __callStatic($method, array $params = array()) {
		if (static::shouldRun()) {
			return call_user_func_array("newrelic_{$method}", $params);
		}
		return false;
	}

	/**
	 *
	 * Determines if we should run any `newrelic_` methods.
	 *
	 * If the configuration for the plugin `shouldRun` does not exist, set
	 * a generic one.
	 *
	 * @return bool
	 */
	public static function shouldRun() {
		if (!is_callable(Libraries::get('li3_newrelic', 'shouldRun'))) {
			$config = Libraries::get('li3_newrelic');
			$config['shouldRun'] = function() {
				return Environment::is('production') && extension_loaded('newrelic');
			};
			Libraries::add('li3_newrelic', $config);
		}
		return Libraries::get('li3_newrelic', 'shouldRun')->__invoke();
	}

	/**
	 * Main dispatch filter to name the transaction.
	 *
	 * Put into a method for better testability.
	 *
	 * @return void
	 */
	public static function filterDispatcher($controller, $params) {
		$class = get_class($controller);
		$class = substr($class, strrpos($class, '\\') + 1);
		$action = $params['request']->params['action'];
		$controllerName = preg_replace('/Controller$/', '', $class);
		$transactionName = "{$controllerName}/{$action}";
		static::name_transaction($transactionName);
	}

}

?>