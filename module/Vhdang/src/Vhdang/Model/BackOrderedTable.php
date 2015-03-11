<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

 

class BackOrderedTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'backordered';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll()
    {
        $select = new Select($this->table);
        $select->columns(array(
            'orderno'   => 'orderno',
            'shipment_id'   => 'shipment_id',             
            'items' => 'items',
            'date' => 'date',
            'finish'    => 'finish'
                     		 
	    ),true)    	
	    
	  ;
	   
	    
	    $resultSet = $this->selectWith($select);
	    $itemst = $resultSet->toArray();
	    $list = array();
	    foreach ($itemst as $row) {	        
	        $item = new CancelledOrders();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
public function getItemByNo($orderno){
		
		$select = new Select($this->table);
		$select   -> columns(array(		    
		    'shipment_id'   => 'shipment_id',		  
		    'orderno' => 'orderno',
		    'items' => 'items',
		    'date'    => 'date',
		    'finish'    => 'finish'
		                   
	          
	    ),true)
	    
	    ->where(array('orderno' => $orderno));
	    $resultSet = $this->selectWith($select);
	 
		$row = $resultSet->current();
		if ($row){
		    $row = (array)$row;
		    $item = new CancelledOrders();
		    $item->setData($row);
		    return $item;
		}else{
		    return null;
		}
		
	}
	

    public function updateItem(BackOrdered $item)
    {
        if ($this->getItemByNo($item->getOrderno())) {
            $data = array( 
                'items' => $item->getItems(),             
                'date' => $item->getDate(),
                
            );
            return $this->update($data, array(
                'orderno' => $item->getOrderno(),
                
            ));
        } else {
            $data = array(               
                'orderno'   => $item->getOrderno(), 
                'shipment_id'   => $item->getShipment_id(),                
                'items' => $item->getItems(),
                'date' => $item->getDate(),
                'finish'    => $item->getFinish()
                
            );
            return $this->insert($data);
        }
    }
	
	public function deleteItem($orderno){
	    return $this->delete(array('orderno' => $orderno));
	}
	
}