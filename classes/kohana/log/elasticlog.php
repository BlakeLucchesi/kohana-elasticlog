<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Elasticsearch log writer. Writes out messages and stores them in a YYYY-MM-DD index.
 *
 * @package    Kohana
 * @category   Logging
 * @author     Blake Lucchesi
 */
class Kohana_Log_Elasticlog extends Log_Writer {
	
	protected $config;
	
	public function __construct() {
		$this->_config = Kohana::$config->load('elasticlog');
	}
	
	public function write(array $messages) {
		foreach ($messages as $message) {
			$documents[] = new Elastica_Document(NULL, $message, 'logs');
		}
		$client = new Elastica_Client($this->_config['connections']);
		$index = $client->getIndex('logs');
		$index->addDocuments($documents);
	}
	
}