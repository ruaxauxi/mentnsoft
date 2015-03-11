<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

 

class BackorderedItemsTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'backordered_items';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll()
    {
        $select = new Select($this->table);
        $select->columns(array(
            'id'    => 'id',
            'order_detail_id' => 'order_detail_id',
            'shipment_id'   => 'shipment_id',
            'nick' => 'nick',
            'items' => 'items',
	        'total'    => 'total',
            'note'  => 'note'
                     		 
	    ),true)    	
	   ->order(array('order_detail_id' => 'DESC'))
	  ;
	  
	    
	    $select->order(array('lastupdated'=>'DESC'));
	    
	    $resultSet = $this->selectWith($select);
	    $itemst = $resultSet->toArray();
	    $list = array();
	    foreach ($itemst as $row) {	        
	        $item = new BackorderedItems();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	
	public function getItemByNo($order_detail_id){
		
		$select = new Select($this->table);
		$select   -> columns(array(
		    'id'    => 'id',
		    'order_detail_id' => 'order_detail_id',
		    'shipment_id'   => 'shipment_id',
		    'nick' => 'nick',
		    'items' => 'items',
		    'total'    => 'total'    ,
		    'note'    => 'note'                 
	          
	    ),true)
	    
	    ->where(array('order_detail_id' => $order_detail_id));
	    $resultSet = $this->selectWith($select);
	 
		$row = $resultSet->current();
		if ($row){
		    $row = (array)$row;
		    $item = new BackorderedItems();
		    $item->setData($row);
		    return $item;
		}else{
		    return null;
		}
		
	}
	
	public function getItemById($id){
	
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'    => 'id',
				'order_detail_id' => 'order_detail_id',
				'shipment_id'   => 'shipment_id',
				'nick' => 'nick',
				'items' => 'items',
				'total'    => 'total'    ,
				'note'    => 'note'
	     
		),true)
		 
		->where(array('id' => $id));
		$resultSet = $this->selectWith($select);
	
		$row = $resultSet->current();
		if ($row){
			$row = (array)$row;
			$item = new BackorderedItems();
			$item->setData($row);
			return $item;
		}else{
			return null;
		}
	
	}
	

    public function updateItem(BackorderedItems $item)
    {
        if ($this->getItemById($item->getId())) {
            $data = array(
                'nick' => $item->getNick(),
                'shipment_id'   => $item->getShipment_id(),
                'items' => $item->getItems(),
                'total' => $item->getTotal(),
                'note'  => $item->getNote()
            );
            return $this->update($data, array(
                'id' => $item->getId()
            ));
        } else {
            $data = array(
                'order_detail_id' => $item->getOrder_detail_id(),
                'shipment_id'   => $item->getShipment_id(),
                'nick' => $item->getNick(),
                'items' => $item->getItems(),
                'total' => $item->getTotal(),
                'note'  => $item->getNote()
            );
            return $this->insert($data);
        }
    }
	
	public function deleteItem($id){
	    return $this->delete(array('id' => $id));
	}
	
}