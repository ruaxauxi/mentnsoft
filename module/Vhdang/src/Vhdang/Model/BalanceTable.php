<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

use Zend\Db\Sql\Expression;
 

class BalanceTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'balance';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'id',
	    		'credit' => 'credit',
	            'lastupdated'  => 'lastupdated'
	    		 
	    )
	    		,true);
	  
	     
	    $resultSet = $this->selectWith($select);
	    $userlist = $resultSet->toArray();
	    $users = array();
	    foreach ($userlist as $row) {	        
	        $user = new Balance();
	        $user->setData($row);
	        $users[] = $user;
	    }
	    return $users;
	    
	    
	}
	
	public function getBalanceByNick($nick){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'id',
				'credit' => 'credit',
				'lastupdated' => 'lastupdated',
				 
		),true)
		->join(array('c' => 'customer'), "$this->table.id = c.balance_id",array())
		 
		->where(array('c.nick' => $nick));
	
	
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		$user = new Balance();
		$user->setData($row);
	
		return $user;
	}
	
	public function getBalanceById($id){
		$id = (int)$id;
		$select = new Select($this->table);
		$select   -> columns(array(
	    		     'id',
    	    		'credit' => 'credit',    	    		 
    	    		'lastupdated' => 'lastupdated',
	          
	    ),true)
	    
	  
	    ->where(array('id' => $id));
	     
	   
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		$user = new Balance();
		$user->setData($row);
		
		return $user;
	}
	
	public function updateBalance($id,$credit){
	    
	    $data = array(
	    		'credit' =>  new Expression('credit + '.$credit)
	    );
	    
	    return $this->update($data,array('id' => $id));
	    
	   
	}
	
	public function saveBalance(Balance $balance){
	    $data = array(	   	        
	        'credit' => $balance->getCredit(),	        
	    );
	    	     
	    $id = $balance->getId();
	   
	    if(!$id){	        
	        $this->insert($data);
	        return $this->getLastInsertValue();
	    }else{
	        if ($this->getBalanceById($id)){
	          return  $this->update($data,array('id' => $id));	             
	        }else{
	            throw new \Exception("The balance object has id $id does not exist.");
	        }
	    }
	    return 0;
	}
	
	public function deleteBalance($id){
	    $this->delete(array('id' => $id));
	}
	
}