<?php
namespace App\Models;

class Address extends \Library\Core\Master\Model {
	public $request;
	public $db;
	
	public function __construct($request) {
		$this->request = $request;
		parent::setDB();
	}
	
	protected function config() {
		$this->db = \App\Core\Config::$database;
	}
	
	public function getContacts() {
		return $this->read('all');	
	}
	
	public function getContact($id) {
		return $this->read('all', array('where'=>array('id'=>$id)));
	}
	
	public function updateAction($conditions, $parameters) {
		$response = $this->update($conditions,$parameters);
		
		return $response;
	}
	
	public function deleteAction($conditions) {
		$response = $this->delete($conditions);
		
		return $response;
	}
	
	public function createAction($parameters) {
		$response = $this->create($parameters);

		return $response;
	}

}
