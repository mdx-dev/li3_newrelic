## li3_newrelic
New Relic plugin for Lithium PHP applications.

[![Build Status](https://secure.travis-ci.org/mdx-dev/li3_newrelic.png?branch=master)](http://travis-ci.org/mdx-dev/li3_newrelic)

## Installation

### Composer
~~~ json
{
    "require": {
        ...
        "mdx-dev/li3_newrelic": "dev-master"
        ...
    }
}
~~~
~~~ bash
php composer.phar install
~~~

### Submodule
~~~ bash
git submodule add git://github.com/mdx-dev/li3_newrelic.git libraries/li3_newrelic
~~~

### Clone Directly
~~~ bash
git clone git://github.com/mdx-dev/li3_newrelic.git libraries/li3_newrelic
~~~

## Setting up
If you do not provide a `shouldRun` key a generic closure will be provided identical to the example below.

~~~ php
<?php
// ...
Libraries::add('li3_newrelic', array(
	'shouldRun' => function() {
		return Environment::is('production') && extension_loaded('newrelic');
	}
));
// ...
?>
~~~

## Usage
The `Newrelic` extension puts an OO wrapper around their built in function calls so calling `Newrelic::notice_error` calls `newrelic_notice_error`. Here is an available list of [php newrelic functions](https://newrelic.com/docs/php/the-php-api).
~~~ php
<?php

namespace app\controllers;

use Exception;
use li3_newrelic\extensions\Newrelic;

class UserController extends \lithium\action\Controller {

	function create() {
		try {
			// some fun stuff here!
		} catch (Exception $e) {
			// Magic
			Newrelic::notice_error('UserCreate/Error', $e);
		}
	}

?>
~~~