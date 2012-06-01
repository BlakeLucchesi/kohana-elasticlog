<?php defined('SYSPATH') or die('No direct script access.');

function __autoload_elastica ($class) {
	$path = str_replace('_', '/', $class);

	if (file_exists('vendor'. DIRECTORY_SEPARATOR . $path . '.php')) {
		require_once('vendor'. DIRECTORY_SEPARATOR . $path . '.php');
	}
}

spl_autoload_register('__autoload_elastica');

Kohana::$log->attach(new Log_Elasticsearch);