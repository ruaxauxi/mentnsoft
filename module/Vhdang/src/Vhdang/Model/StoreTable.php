<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Expression;


class StoreTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'store';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function getStoreStat($store_id="",$status="waiting"){
	    
	    $select = new Select($this->table);
	    
	    $select->columns(array(
	        'id',
	        'name' => 'name'	       
	    ))
	    ->join(array('co' => 'customer_order'),"$this->table.id=co.store_id",array(
	    	'total' => new Expression('count(*)')
	    ))
	    ->where(array(
	    	'co.status' => $status
	    ))
	    ->group(array(
	    	'id'
	    ))
	    ->order(array(
	    	'total'   => 'DESC'
	    ));
	    
	    if ($store_id){
	        $select->where(array(
	        	'store_id'  => $store_id
	        ));
	    }
	    
	    $resultSet = $this->selectWith($select);
	    $list = $resultSet->toArray();
	    $items = array();
	    foreach ($list as $row) {
	    	$store = new Store();
	    	$store->setData($row);
	    	$items[] = $store;
	    }
	    return $items;
	}
	
	
	public function fetchAll($like=null){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'id',
	    		'name' => 'name', 
	    )
	    		,true)
	    ->order(array('name'=> 'ASC'))
	    ->where(array('active'=> 1))
	    ;
	  
	    if ($like){
	        $select->where->like('name', $like . '%');	        
	    }
	   
	     
	    $resultSet = $this->selectWith($select);
	    $list = $resultSet->toArray();
	    $items = array();
	    foreach ($list as $row) {	        
	        $store = new Store();
	        $store->setData($row);
	        $items[] = $store;
	    }
	    return $items;
	    
	}
	
	public function getStoreById($id){
		$id = (int)$id;
		$select = new Select($this->table);
		$select   -> columns(array(
	    		     'id',
    	    		'name' => 'name',    	   
	          
	    ),true)
	    ->where(array('id' => $id,'active'=> 1));
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		 $store = new Store();
	     $store->setData($row);
		
		return $store;
	}
	
	public function getStoreByName($name){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'id',
				'name' => 'name',
				 
		),true)
		->where->addPredicate(
		      new \Zend\Db\Sql\Predicate\Like('name',$name)
		    )
		
		;

		$select->where(array('active'    => 1));
		 
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		$store = new Store();
		$store->setData($row);
	
		return $store;
	}
	
	
	public function saveStore(Store $store){
	    $data = array(	   	        
	        'name' => $store->getName()	        
	    );
	    	     
	    $id = $store->getId();
	   
	    if(!$id){	        
	        $this->insert($data);
	        return $this->getLastInsertValue();
	    }else{
	        if ($this->getStoreById($id)){
	            return $this->update($data,array('id' => $id));
	             
	        }else{
	            throw new \Exception("The balance object has id $id does not exist.");
	        }
	    }
	    return 0;
	}
	
	public function deleteStore($id){
	    $this->delete(array('id' => $id));
	}
	
}