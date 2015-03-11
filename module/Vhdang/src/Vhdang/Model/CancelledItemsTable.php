<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

 

class CancelledItemsTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'cancelled_items';
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
	        'total'    => 'total'
                     		 
	    ),true)    	
	   ->order(array('order_detail_id' => 'DESC'))
	  ;
	  
	    
	    $select->order(array('lastupdated'=>'DESC'));
	    
	    $resultSet = $this->selectWith($select);
	    $itemst = $resultSet->toArray();
	    $list = array();
	    foreach ($itemst as $row) {	        
	        $item = new CancelledItems();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	
	public function fetchAllByShipment($shipment_id)
	{
		$select = new Select($this->table);
		$select->columns(array(
				'id'    => 'id',
				'order_detail_id' => 'order_detail_id',
				'shipment_id'   => 'shipment_id',
				'nick' => 'nick',
				'items' => 'items',
				'total'    => 'total'
	
		),true)
		->join(array('od' => 'order_details'),"od.id = $this->table.order_detail_id",array(
			'orderno' => 'orderno'
		))
		->order(array(
		    'orderno' => 'DESC',
		    'order_detail_id' => 'DESC'
		))
		->where(array(
			'shipment_id' => $shipment_id
		))
		;
		 
		 
		//$select->order(array('lastupdated'=>'DESC'));
		 
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new CancelledItems();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	
	/**
	 * do cac item bi cancel co the nam trong dot hang hoac chua add vao dot nao
	 * do do can phai join voi shipment orders de lay shipment id
	 * @abstract tong so tien bi cancel theo shipment id
	 * @param unknown $shipment_id
	 * @return Ambigous <>
	 */
	public function getTotalByShipmentId($shipment_id){
	   /*  $select = new Select($this->table);
	    $select->columns(array(
	    	'total'   => new Expression('sum(cancelled_items.total)')
	    ))->where(array(
	    	'shipment_id' => $shipment_id
	    ));
	    
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->current(); */
	    
	    
	    $select = new Select($this->table);
	    $select->columns(array(
	    		'total'   => new Expression('sum(cancelled_items.total)')
	    ))
	    ->join(array("od" => 'order_details'), "od.id = $this->table.order_detail_id",array())
	    ->join(array('so'=>'shipment_order'), "so.orderno = od.orderno",array())
	    ->where(array(
	    		'so.shipment_id' => $shipment_id
	    ));
	     
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->current();
	    
	    
	    return $result['total'];
	    
	}
	
	public function getTotalByOrderno($orderno){
	    
	    
	    /* $subselect = new Select('order_details');
	    $subselect->columns(array(
	    		'order_detail_id' => 'id'
	    ), true)
	    ->where(array(
	    	'orderno' => $orderno
	    ));
	     */
	    
	    $select = new Select($this->table);
	    
	    $select->columns(array(
	        'total' => new Expression("sum($this->table.total)")
	        
	    ))->join(array("od" => "order_details"),"$this->table.order_detail_id = od.id",array())
	    ->where(array(
	    	'od.orderno'  => $orderno
	    ));
	    
	   /*  $select->columns(array(	    		 
	    		'total' => new Expression('sum(total)')
	    ), true)->where(array(
	    		new \Zend\Db\Sql\Predicate\In('order_detail_id', $subselect)
	    )); */
	    
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->current();
	     
	    return $result['total'];
	    
	}
	
	public function getTotalItemByOrderno($orderno){
	    $select = new Select($this->table);
	    
	    $select->columns(array(
	    	'total' => new Expression("sum($this->table.items)")
	    ))->join(array('od' => 'order_details'), "$this->table.order_detail_id = od.id",array())
	    ->where(array(
	    	'orderno' => $orderno
	    ));
	    
	    $resultSet = $this->selectWith($select);
	    $row = $resultSet->current();
	    
	    return $row['total'];
	    
	}
	
	public function getItemByNo($order_detail_id){
		
		$select = new Select($this->table);
		$select   -> columns(array(
		     'id' => 'id',
		    'order_detail_id' => 'order_detail_id',
		    'shipment_id'   => 'shipment_id',
		    'nick' => 'nick',
		    'items' => 'items',
		    'total'    => 'total'                     
	          
	    ),true)
	    
	    ->where(array('order_detail_id' => $order_detail_id));
	    $resultSet = $this->selectWith($select);
	 
		$row = $resultSet->current();
		if ($row){
		    $row = (array)$row;
		    $item = new CancelledItems();
		    $item->setData($row);
		    return $item;
		}else{
		    return null;
		}
		
	}
	
	public function saveItem(CancelledItems $item){
	     $data = array(
                'order_detail_id' => $item->getOrder_detail_id(),
                'shipment_id'   => $item->getShipment_id(),
                'nick' => $item->getNick(),
                'items' => $item->getItems(),
                'total' => $item->getTotal()
            );
	     
	     
	     if (!$item->getShipment_id()){
	     	unset($data['shipment_id']);
	     }
	     
	     return $this->insert($data);
	      
	     
	}

    
	
	public function deleteItem($id){
	    return $this->delete(array('id' => $id));
	}
	
}