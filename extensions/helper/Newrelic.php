<?php

namespace li3_newrelic\extensions\helper;

use li3_newrelic\extensions\Newrelic as NewrelicCore;

class Newrelic extends \lithium\template\Helper {

	/**
	 * Inserts New Relic "Real User Monitoring" header script when called.
	 *
	 * Should be placed in the header
	 *
	 * @param bool $includeScriptTag
	 * @return mixed
	 */
	public function header($includeScriptTag = true) {
		return NewrelicCore::get_browser_timing_header($includeScriptTag);
	}

	/**
	 * Inserts New Relic "Real User Monitoring" footer script when called.
	 *
	 * Should be placed right before </body>
	 *
	 * @param bool $includeScriptTag
	 * @return mixed
	 */
	public function footer($includeScriptTag = true) {
		return NewrelicCore::get_browser_timing_footer($includeScriptTag);
	}
}

?>