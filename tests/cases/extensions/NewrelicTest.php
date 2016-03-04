<?php

namespace li3_newrelic\tests\cases\extensions;

use stdClass;
use lithium\test\Unit;
use lithium\test\Mocker;
use lithium\core\Libraries;
use li3_newrelic\extensions\newrelic\Mock as NewrelicMock;

class NewrelicTest extends Unit {

	public function setUp() {
		Mocker::register();
		$this->selfConfig = Libraries::get('li3_newrelic');
	}

	public function tearDown() {
		NewrelicMock::applyFilter(false);
		Mocker::overwriteFunction(false);
		Libraries::add('li3_newrelic', $this->selfConfig);
	}

	public function testFilterDispatcherGensCorrectTransaction() {
		// Setup
		$params = array('request' => new stdClass);
		$params['request']->params = array(
			'action' => 'foobar',
		);
		Mocker::overwriteFunction('li3_newrelic\extensions\get_class', function() {
			return 'foo\bar\BazController';
		});
		NewrelicMock::applyFilter('shouldRun', function($self, $params, $chain) {
			return false;
		});

		// Call it
		NewrelicMock::filterDispatcher(new stdClass, $params);
		$chain = Mocker::chain('li3_newrelic\extensions\newrelic\Mock');

		// Assert
		$chain->called('__callStatic')->with('name_transaction', array('Baz/foobar'));
		$this->assertTrue($chain->success());
	}

	public function testShouldRunSetsLibraryConfig() {
		$config = $this->selfConfig;
		unset($config['shouldRun']);
		Libraries::add('li3_newrelic', $this->selfConfig);

		$result = NewrelicMock::shouldRun();

		$config = Libraries::get('li3_newrelic');
		$this->assertArrayHasKey('shouldRun', $config);
		$this->assertInternalType('bool', $result);
	}

	public function testCallStaticCallsCorrectFunction() {
		NewrelicMock::applyFilter('shouldRun', function($self, $params, $chain) {
			return true;
		});

		Mocker::overwriteFunction('li3_newrelic\extensions\call_user_func_array', function($function_name) {
			return $function_name;
		});

		$result = NewrelicMock::custom_metric();

		$this->assertIdentical('newrelic_custom_metric', $result);
	}

	public function testCallStaticReturnsFalseWhenShouldNotRun() {
		NewrelicMock::applyFilter('shouldRun', function($self, $params, $chain) {
			return false;
		});
		$result = NewrelicMock::custom_metric();

		$this->assertIdentical(false, $result);
	}

}

?>