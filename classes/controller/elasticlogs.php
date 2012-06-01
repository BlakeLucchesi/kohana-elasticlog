<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Elasticlogs extends Controller {
	
	public function action_index() {
		$config = Kohana::$config->load('elasticlog');
		$client = new Elastica_Client($config['connections']);
		$search = new Elastica_Search($client);
		$search->addIndex('logs')->addType('log');
		print Debug::vars($search->search());
		// print Debug::vars($query);
		// $response = $client->request('logs/_search', 'GET', array(), $query);
		// print Debug::vars($response);
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