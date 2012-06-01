<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Elasticlogs extends Controller {
	
	public function action_index() {
		Kohana::$log->add(Log::NOTICE, "Searching through logs");
		$config = Kohana::$config->load('elasticlog');
		$client = new Elastica_Client($config['connections']);
		$index = $client->getIndex('logs');
		
		$query = new Elastica_Query_MatchAll();
		
		// $query = new Elastica_Query_Fuzzy();
		// $query->addField('body', array('value' => 'HTTP_Exception_404'));
		
		$results = $index->search($query);
		print View::factory('elasticlog/logs')->bind('results', $results);
	}
	
	public function action_install() {
		// Create the index new
		$config = Kohana::$config->load('elasticlog');
		$client = new Elastica_Client($config['connections']);
		$index = $client->getIndex('logs');
		$index->create(array(
			'number_of_shards' => 5,
			'number_of_replicas' => 1,
			'analysis' => array(
				'analyzer' => array(
					'indexAnalyzer' => array(
						'type' => 'custom',
						'tokenizer' => 'standard',
						'filter' => array('lowercase')
					),
					'searchAnalyzer' => array(
						'type' => 'custom',
						'tokenizer' => 'standard',
						'filter' => array('standard', 'lowercase')
					)
				),
			)
		), true);
		
		// Load type
		$type = $index->getType('log');

		// Define mapping
		$mapping = new Elastica_Type_Mapping();
		$mapping->setType($type);
		$mapping->setParam('index_analyzer', 'indexAnalyzer');
		$mapping->setParam('search_analyzer', 'searchAnalyzer');

		// Set mapping
		$mapping->setProperties(array(
			'level' => array('type' => 'integer', 'include_in_all' => TRUE),
			'body'  => array('type' => 'string', 'include_in_all' => TRUE),
			'time'  => array('type' => 'date', 'include_in_all' => FALSE),
		));

		// Send mapping to type
		$mapping->send();
	}
}