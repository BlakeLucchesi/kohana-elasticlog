<?php defined('SYSPATH') or die('No direct script access.');

function __autoload_elastica ($class) {
	$path = str_replace('_', '/', $class);
	$filepath = DIRNAME(__FILE__) .'/vendor/elastica/lib/' . $path . '.php';
	if (file_exists($filepath)) {
		require_once($filepath);
	}
}

spl_autoload_register('__autoload_elastica');

Kohana::$log->attach(new Log_Elasticlog);

Route::set('elasticlogs', 'logs(/<action>)')
	->defaults(array(
		'controller' => 'elasticlogs',
		'action' => 'index'
	));