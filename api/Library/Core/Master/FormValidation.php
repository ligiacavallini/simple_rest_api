<?php
namespace Library\Core\Master;

abstract class FormValidation {	
	public function formValidation($rules, $parameters) {
		$message_error = '';
		foreach($rules as $key => $value):
			if(!array_key_exists($key, $parameters)) {
				if($value['required'] === true){
					$message_error .= ucfirst($key)." must be provided\n";
				}
			} else {
				if($value['required'] === true) {
					if($this->required($parameters[$key])===false) {
						$message_error .= ucfirst($key)." must be provided and can not be null. ";
					}
				}
				
				if(isset($value['type']) && $value['type'] != '') {
					if($this->$value['type']($parameters[$key])===false) {
						$message_error .= "You must provide a valid ".ucfirst($key).". ";
					}
				}
				
				if(isset($value['size']) && $value['size'] != '') {
					if($this->checkSize($parameters[$key], $value['size'])===false) {
						$message_error .= ucfirst($key)." must not have more than ".$value['size']." characters. ";
					}
				}
				
			}
			
		endforeach;
		if($message_error != '') {
			$response = array('status'=>'error', 'message'=> $message_error);
			unset($message_error);
		}
		
		return (isset($response) ? $response : true);
	}
	
	protected function required($input = null) {
        return (!is_null($input) && (trim($input) != ''));
    }
    
    protected function numeric($input) {
        return is_numeric($input);
    }
    
    protected function email($input) {
        return filter_var($input, FILTER_VALIDATE_EMAIL);
    }
    
    protected function integer($input) {
        return is_int($input) || ($input == (string) (int) $input);
    }
    
    protected function name($input) {
        return (preg_match("#^[a-zA-Z ]+$#", $input) == 1);
    }
    
    protected function text($input) {
        return (preg_match("#^[a-zA-Z0-9 ]+$#", $input) == 1);
    }
    
    protected function checkSize($input, $size) {
        return (strlen($input) <= $size);
    }

}
