<?php
class Route {
	use \Library\Core\Utilities\URIstatus;
	
	protected $model;
	protected $controller;
	
	public function __construct($request) { 
		$this->_loadModel($request);
		$result = $this->_loadController($request);
		$this->_loadView($request, $result);
	}
	
	private function _loadView($request, $result) {
		
		$view_name = '\App\Views\\'.ucfirst($request->format);
		
		if(class_exists($view_name)) {
			$view = new $view_name();
			$view->render($result);
		}
	}
	
	private function _loadController($request) {
		$controller_name = '\App\Controllers\\'.ucfirst($request->url_elements[1]);
		if (class_exists($controller_name)) {
			$this->controller = new $controller_name($request, $this->model);
			
			$action_name = strtolower($request->verb);
			return $this->_processAPI($this->controller, $action_name, $request);
		}else{
			return $this->response('Not Found', 404);
		}	
	}
	
	private function _loadModel($request) {
		$model_name = '\App\Models\\'.ucfirst($request->url_elements[1]);
		$model_file_name = ROOT.DS.'App'.DS.'Models'.DS.ucfirst($request->url_elements[1]).'.php';
		if (file_exists($model_file_name) && class_exists($model_name)) {
			$this->model = new $model_name($request);
		}else{
			$this->model = null;
		}		
	}
	
	private function _processAPI($controller, $action_name, $request) {
		switch($action_name) {
			case 'delete':
			case 'post':
			case 'get':
			case 'put':
				if ((int)method_exists($controller, $action_name) > 0) {
					return $this->response($controller->{$action_name}($request));
				}
				return $this->response("Invalid method: $action_name", 404);
				break;
			default:
				$this->response('Invalid Method', 405);
				break;
        } 
    }

}