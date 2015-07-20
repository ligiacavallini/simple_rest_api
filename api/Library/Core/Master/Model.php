<?php
namespace Library\Core\Master;

use PDO;

abstract class Model extends \Library\Core\Master\Suport\Pdo
{
	public $table;
	public $mysql;
    protected $mysql_exec;
    
	abstract protected function config();
	
    public function setDB() {
		$this->config();
		if(!isset($this->table)){
			$this->table = strtolower($this->request->url_elements[1]); 
		}
        
        if ((isset($this->db['servidor'])) and (isset($this->db['usuario'])) and (isset($this->db['senha']))) {
            $this->mysql = new PDO(
                'mysql:host='.$this->db['servidor'].';dbname='.$this->db['database'], $this->db['usuario'], $this->db['senha']
            );
            $this->mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
	
    public function read($tipo='all', Array $configs=array()) {
        $save['sql']='';
        $save['save']=array();
        $campos ='';
        
        if (!isset($configs['campos'])) {
            $campos='*';
        } else {
            $total=count($configs['campos']);
            $i=1;
            foreach ($configs['campos'] as $v) {
                $campos.='`'.$v.'`';
                if ($i!=$total) {
                    $campos .=' , ';
                }
                $i++;
            }
        }
        
        if (isset($configs['where'])) {
            $ret=$this->getWhere($configs['where']);
            $save['sql'] .= $ret['sql'];
            $save['save']=array_merge($ret['save'], $save['save']);
        }
        
        $sql = 'SELECT '.$campos.' FROM `'.$this->table.'`'.$save['sql'].';';
        
        $this->pdo('query', $sql, $save['save']);
        
        if ($tipo=='first') {
            return $this->mysql_exec->fetch(PDO::FETCH_ASSOC);
        } elseif ($tipo=='all') {
            return $this->mysql_exec->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($tipo=='count') {
            return $this->mysql_exec->rowCount();
        }
    }
	
	public function create(Array $campos) {
        
        $save=$this->getCampos($campos);
        
        $fields = implode('`,`', array_keys($campos));
        $values = implode(',', array_keys($save['save']));
        
        $sql='INSERT INTO `'.$this->table.'` (`'.$fields.'`) VALUES('.$values.');';
        
        $response = $this->pdo('insert', $sql, $save['save']);
        
        if($response===false || $response == 'no rows'){
			$return['status'] = 'error';
			$return['message'] = 'The data could not be submitted';
		}else{
			$return['status'] = 'ok';
			$return['id'] = $response;
			$return['message'] = 'The data was submitted';
		}
		return $return;
    }
    
    public function update(Array $conditions,Array $campos) {
        
        $save=$this->getCampos($campos);
        
        $conditions=$this->getWhere($conditions);
        
        $save['save']=array_merge($save['save'], $conditions['save']);
        
        $sql='UPDATE `'.$this->table.'` SET '.$save['sql'].' '.$conditions['sql'].';';

        $response = $this->pdo('update', $sql, $save['save']);
        
        if($response == 'no rows'){
        	$return['status'] = 'error';
			$return['message'] = 'The entry was not updated';
        }elseif($response===false){
			$return['status'] = 'error';
			$return['message'] = 'The data could not be updated.';
		}else{
			$return['status'] = 'ok';
			$return['message'] = 'The data was updated.';
		}
		
		return $return;
        
    }
    
    public function delete(Array $conditions) {
        $save=$this->getWhere($conditions);
        
        $sql = 'DELETE FROM `'.$this->table.'` '.$save['sql'].';';
        $response = $this->pdo('delete', $sql, $save['save']);
        
        if($response== 'no rows'){
        	$return['status'] = 'error';
			$return['message'] = 'The data was not deleted';
        }elseif($response===false){
			$return['status'] = 'error';
			$return['message'] = 'The data could not be deleted';
		}else{
			$return['status'] = 'ok';
			$return['message'] = 'The data was deleted';
		}
		
		return $return;
    }
 
}
