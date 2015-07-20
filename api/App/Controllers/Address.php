<?php
namespace App\Controllers;

class Address extends \Library\Core\Master\FormValidation {
	public $model;
	public $validation_rules;
	
	public function __construct($request, $model) {
		$this->model = $model;	
		$this->validation_rules = array('name'=> array('required' => true,
														'type' =>  'name',
														'size' => 150),
										'phone'=> array('required' => true,
														'type' =>  'numeric',
														'size' => 9),
										'street'=> array('required' => true,
														'size' => 150));
	}
	
    public function get($request) {
        if(isset($request->url_elements[2])) {
            $user_id = (int)$request->url_elements[2];
            $data = $this->model->getContact($user_id);
        } else {
            $data = $this->model->getContacts();
        }
        return $data;
    }
 
	public function post($request) {	
		//if there is an id, should do an update and not an insert
		if(isset($request->url_elements[2])) {
			
            $response = $this->put($request);
		}else{
			$response = $this->formValidation($this->validation_rules, $request->parameters);
		
			if($response['status']=='error')
				return $response;
		
			$response = $this->model->createAction($request->parameters);	
		}
		return $response;
    }
	
	public function put($request) {
		if(isset($request->url_elements[2])) {
			$conditions = array('id'=>(int)$request->url_elements[2]);
			$response = $this->formValidation($this->validation_rules , $request->parameters);
			
			if($response['status']=='error')
				 return $response;
			
			$response = $this->model->updateAction($conditions, $request->parameters);
		}else{
			$response = array('status'=>'error','message'=>'You must provide an id to update the data');
		}
		return $response;
    }
	
	public function delete($request) {
		if(isset($request->url_elements[2])) {
			$conditions = array('id'=>(int)$request->url_elements[2]);
			$response = $this->model->deleteAction($conditions);
		}else{
			$response = array('status'=>'error','message'=>'You must provide an id to delete the data');
		}
		
		return $response;
	}
}
