<?php
namespace Library\Core\Master\Suport;

class Pdo {
    protected function getCampos(Array $campos) {
        $save['sql']='';
        foreach ($campos as $key => $value) {
            $save['sql'].='`'.$key.'` = :'.$key.', ';
            $save['save'][':'.$key]=$value;            
        }
        $save['sql'] = substr($save['sql'],0,-2);
        return $save;
    }
	
	protected function getWhere(Array $where) {
        $save['sql']='where ';
        foreach ($where as $key => $value) {
            $save['sql'].='`'.$key.'` = :'.$key.' ';
            $save['save'][':'.$key]=$value;            
        }
        return $save;
    }
    
    protected function pdo($type, $sql, $values) {
        $this->pdoPrepare($sql);
		$this->pdoBindValue($values);
		$response = $this->pdoExecute($type, $values);	
		return $response;
    }
    
    protected function pdoPrepare($sql) {
		try
		{
			$this->mysql_exec=$this->mysql->prepare($sql);
		}
		catch( PDOException $Exception ) 
		{
			echo $Exception;
		}
    }
    
    protected function pdoBindValue($values) {
        foreach ($values as $k=>$v) {
            $this->mysql_exec->bindValue(':'.$k, $v);
        }
    }
    
    protected function pdoExecute($type, Array $values=array()) {
    	
		if($this->mysql_exec->execute($values)){
		
			switch($type){
				case 'insert':
					if($this->mysql_exec->rowCount() > 0)
						return ($this->mysql->lastInsertId() ? $this->mysql->lastInsertId() : true);
					else
						return 'no rows';
						
					break;
				case 'update':
				case 'delete':
					if($this->mysql_exec->rowCount() > 0)
						return $this->mysql_exec->rowCount();
					else
						return 'no rows';
						
					break;
			}
			
		}else{
			return false;
		}
    }
}
