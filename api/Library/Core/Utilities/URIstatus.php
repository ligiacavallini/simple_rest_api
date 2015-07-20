<?php
namespace Library\Core\Utilities;

trait URIstatus {
	public function response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return $data;
    }
	
	protected function _requestStatus($code) {
        $status = array(  
            200 => 'OK',
            404 => 'Not found ',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }
}
