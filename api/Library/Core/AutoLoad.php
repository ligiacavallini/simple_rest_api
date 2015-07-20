<?php
include ('Utilities'.DS.'URIstatus.php');

class AutoLoad {
	use \Library\Core\Utilities\URIstatus;

	public function setPath($path) {
        set_include_path($path);
    }
	
	public function setExt($ext)
    {
        $this->ext='.'.$ext;
    }
	
	public function loadCore($className) {
        $fileName=$this->setFilename($className);
        $fileName=get_include_path().DS.'Library'.DS.'Core'.DS.$fileName;
        
        if (is_readable($fileName)) {
            include $fileName;
        }
    }
	
	public function loadCoreApp($className) {
        $fileName=$this->setFilename($className);
        $fileName=get_include_path().DS.'App'.DS.'Core'.DS.$fileName;
        
        if (is_readable($fileName)) {
            include $fileName;
        }
    }
	
	public function load($className) {
        $fileName=$this->setFilename($className);
        $fileName=get_include_path().DS.$fileName;
        
        if (is_readable($fileName)) {
            include $fileName;
        }else {
            $this->response('Not found', 404);
        } 
    }
	
	protected function setFilename($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $className = $className;
            $fileName  = str_replace('\\', DS, $namespace) . DS;
        }
        $fileName .= str_replace('_', DS, $className) . $this->ext;
        return $fileName;
    }
}
