<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Sql;

 

class ShippingFeeTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'shipping_fee';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function getTotalByShipmentId($shipment_id){
	    $select = new Select($this->table);
	    
	    $select->columns(array(
	    	'total'   => new \Zend\Db\Sql\Expression('sum(total)')
	    ))->where(array(
	    	'shipment_id' => $shipment_id
	    ));
	    
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->current();
	    
	    return $result['total'];
	} 
	 
	
	public function getTotalWeightByShipmentId($shipment_id){
		$select = new Select($this->table);
		 
		$select->columns(array(
				'total'   => new \Zend\Db\Sql\Expression('sum(weight)')
		))->where(array(
				'shipment_id' => $shipment_id
		));
		 
		$resultSet = $this->selectWith($select);
		$result = $resultSet->current();
		 
		return $result['total'];
	}
	
	
	public function getShippingFeeByShipmentId($shipment_Id){
	    $select = new Select($this->table);
	    
	    $select->columns(array(
	    	'id'  => 'id',
	        'shipment_id' => 'shipment_id',
	        'admin'    => 'admin',
	        'weight'   => 'weight',
	        'total'    => 'total',
	        'note'     => 'note',
	        'date'     => 'date'
	    ),true)
	    ->where(array('shipment_id' => $shipment_Id))
	    ->order(array('date' => 'DESC'));
	    
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->toArray();
	    
	    $list = array();
	    foreach($result as $row){
	        $item = new ShippingFee();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    
	    return $list;
	    
	}
	
	/* public function getShippingWeightByKey($shipment_id,$nick){
		
		$select = new Select($this->table);
		$select->columns(array(
            'nick' => 'nick',
            'shipment_id' => 'shipment_id',
            'date' => 'date',
            'note' => 'note',
            'weight' => 'weight',
            'price' => 'price',
            'total' => 'total'
        ), true)
	    ->where(array('shipment_id' => $shipment_id,'nick' => $nick));
	     
		 
	    $resultSet = $this->selectWith($select);
		
		$row = $resultSet->current();
		 
		if ($row){		    
		    $item = new ShippingWeight();
		    $row = (array)$row;
		    $item->setData($row);
		    return $item;
		}else{
		    return null;    
		}
		
	} */
	
	public function insertShippingFee(ShippingFee $shippingfee){
		$data = array(
            'shipment_id' =>  $shippingfee->getShipment_id(),
            'admin' =>  $shippingfee->getAdmin(),
		    'weight'  => $shippingfee->getWeight(),
            'total' =>  $shippingfee->getTotal(),
            'note' =>  $shippingfee->getNote(),
            'date' =>  $shippingfee->getDate()
             
        );
	 
		return $this->insert($data);
	}
	
	
	 
	
	
	public function deleteShippingFee($id){
	    return $this->delete(array('id' => $id));
	}
	
}