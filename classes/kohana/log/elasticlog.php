<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Elasticsearch log writer. Writes out messages and stores them in a YYYY-MM-DD index.
 *
 * @package    Kohana
 * @category   Logging
 * @author     Blake Lucchesi
 */
class Kohana_Log_Elasticlog extends Log_Writer {
	
	public function __construct() {
		$config = Kohana::$config->load('elasticsearch');
	}
	
	public function write(array $messages) {
		
	}
	
}