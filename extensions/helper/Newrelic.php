<?php

namespace li3_newrelic\extensions\helper;

use \lithium\template\Helper;

class Newrelic extends Helper {

	/**
	 * Inserts New Relic "Real User Monitoring" header script when called. Should be placed in the header
	 * @param bool $includeScriptTag
	 * @return mixed
	 */
	public function header($includeScriptTag = true) {
		if (Environment::is('production') && extension_loaded ('newrelic')) {
		  return newrelic_get_browser_timing_header($includeScriptTag);
		}
	}

	/**
	 * Inserts New Relic "Real User Monitoring" footer script when called. Should be placed right before </body>
	 * @param bool $includeScriptTag
	 * @return mixed
	 */
	public function footer($includeScriptTag = true) {
		if (Environment::is('production') && extension_loaded ('newrelic')) {
		    newrelic_get_browser_timing_footer($includeScriptTag);
		}
	}
}
?>