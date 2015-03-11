<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
 
class ShippingMethodTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'shipping_method';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'id',
	    		'shipping_method' => 'shipping_method', 
	    )
	    		,true)
	    ->order(array('id'=> 'ASC'))	     
	    ;
	  
	   
	    $resultSet = $this->selectWith($select);
	    $list = $resultSet->toArray();
	    $items = array();
	    foreach ($list as $row) {	        
	        $store = new ShippingMethod();
	        
	        $store->setData($row);
	        $items[] = $store;
	    }
	    return $items;
	    
	}
	
	public function getShippingMethodById($id){
		$id = (int)$id;
		$select = new Select($this->table);
		$select   -> columns(array(
	    		     'id',
    	    		'shipping_method' => 'shipping_method',    	   
	          
	    ),true)
	    ->where(array('id' => $id));
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		 $store = new ShippingMethod();
	     $store->setData($row);
		
		return $store;
	}
	
	 
	
	
	public function saveStore(ShippingMethod $s){
	    $data = array(	   	        
	        'shipping_method' => $s->getShipping_method(),
	        'id'   => $s->getId()	        
	    );
	    
	    
	    
	    if(!$this->getShippingMethodById($s->getId())){	        
	        $this->insert($data);
	        return $this->getLastInsertValue();
	    }else{
	        return $this->update($data,array('id' => $s->getId()));
	    }
	    
	}
	
	public function deleteShippingMethod($id){
	    $this->delete(array('id' => $id));
	}
	
}