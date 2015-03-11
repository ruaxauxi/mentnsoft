<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Sql;

 

class AdditionalFeeTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'additional_fee';
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
	 
	public function getAdditionalFeeByShipmentId($shipment_Id){
	    $select = new Select($this->table);
	    
	    $select->columns(array(
	    	'id'  => 'id',
	        'shipment_id' => 'shipment_id',
	        'admin'    => 'admin',
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
	        $item = new AdditionalFee();
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
	
	public function insertAdditionalFee(AdditionalFee $additionalfee){
		$data = array(
            'shipment_id' =>  $additionalfee->getShipment_id(),
            'admin' =>  $additionalfee->getAdmin(),
            'total' =>  $additionalfee->getTotal(),
            'note' =>  $additionalfee->getNote(),
            'date' =>  $additionalfee->getDate()
             
        );
	 
		return $this->insert($data);
	}
	
	
	 
	
	
	public function deleteAdditionalFee($id){
	    return $this->delete(array('id' => $id));
	}
	
}