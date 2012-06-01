<?php defined('SYSPATH') or die('No direct script access.');

$config = array();

$config['hosts'] = array(
	array(
		'hostname' => 'localhost',
		'port' => '9300',
	)
);

return $config;