<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

 

class CancelledOrdersTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'cancelled_orders';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll()
    {
        $select = new Select($this->table);
        $select->columns(array(
            'id'        => 'id',
            'orderno'   => 'orderno',
            'shipment_id'   => 'shipment_id',
            'admin'  => 'admin',
            'items' => 'items',
            'total' => 'total',
            'total_web1'    => 'total_web1',
                     		 
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
	
	
	public function fetchAllByShipment($shipment_id)
	{
		$select = new Select($this->table);
		$select->columns(array(
				'id'        => 'id',
				'orderno'   => 'orderno',
				'shipment_id'   => 'shipment_id',
				'admin'  => 'admin',
				'items' => 'items',
				'total' => 'total',
				'total_web1'    => 'total_web1',
	
		),true)
		->where(array(
			'shipment_id' => $shipment_id
		));
		 
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
	
	
	public function getTotalWeb1ByOrderNo($orderno){
	
		$select = new Select($this->table);
		$select   -> columns(array(
				'total'    => new Expression('sum(total_web1)'),
					
		),true)
			
		->where(array('orderno' => $orderno));
		$resultSet = $this->selectWith($select);
	
		$row = $resultSet->current();
	
		return $row['total'];
	
	}
	
	public function getTotalByOrderNo($orderno){
	
		$select = new Select($this->table);
		$select   -> columns(array(				 
				'total'    => new Expression('sum(total)'),
			 
				 
		),true)
		 
		->where(array('orderno' => $orderno));
		$resultSet = $this->selectWith($select);
	
		$row = $resultSet->current();
		
		return $row['total'];
	
	} 
	
	public function getTotalFinalByShipmentId($shipment_id){
		$select = new Select($this->table);
		$select->columns(array(
				'total'   => new Expression('sum(total)')
		))->where(array(
				'shipment_id' => $shipment_id
		));
			
		$resultSet = $this->selectWith($select);
		$result = $resultSet->current();
			
		return $result['total'];
			
	}
	
	
	public function getTotalByShipmentId($shipment_id){
		$select = new Select($this->table);
		$select->columns(array(
				'total'   => new Expression('sum(total_web1)')
		))->where(array(
				'shipment_id' => $shipment_id
		));
		 
		$resultSet = $this->selectWith($select);
		$result = $resultSet->current();
		 
		return $result['total'];
		 
	}
	
    public function getItemByNo($id){
		
		$select = new Select($this->table);
		$select   -> columns(array(
		    'id'        => 'id',
		    'shipment_id'   => 'shipment_id',
		    'admin' => 'admin',
		    'orderno' => 'orderno',
		    'items' => 'items',
		    'total'    => 'total',
		    'total_web1'    => 'total_web1',
		                   
	          
	    ),true)
	    
	    ->where(array('id' => $id));
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
	

    public function updateItem(CancelledOrders $item)
    {
        $data = array(
        		'admin' => $item->getAdmin(),
        		'items' => $item->getItems(),
        		'total' => $item->getTotal(),
        		'total_web1'    => $item->getTotal_web1(),
        		'orderno' => $item->getOrderno(),
        		'shipment_id'   => $item->getShipment_id()
        
        );
        
        if (!$item->getShipment_id()){
            unset($data['shipment_id']);
        }
        
        if ($this->getItemByNo($item->getId())) {
            
            return $this->update($data, array(
               'id' => $item->getId()
            ));
        } else {             
            return $this->insert($data);
        }
    }
	
	public function deleteItem($orderno,$shipment_id){
	    return $this->delete(array('orderno' => $orderno,'shipment_id'=>$shipment_id));
	}
	
}