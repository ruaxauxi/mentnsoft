<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Sql;

 

class ExpressShippingFeeTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'express_shipping_fee';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function getAll($shipment_id){
	    $select = new Select($this->table);
	    $select->columns(array(
	        'id' => 'id',
	        'shipment_id' =>  "shipment_id",
	        'admin' =>  'admin',
	        'nick' =>  'nick',
	        'fee'     => 'fee',
	        'usd'  => 'usd',
	        'xrate'    => 'xrate',
	        'note' =>  'note',
	        'date' => 'date'
	    ),true)
	    ->where(array(
	        'shipment_id'  =>$shipment_id
	    ))
	    ->order(array(
	        'nick' => 'DESC'
	    ));
	    
	    $resultSet = $this->selectWith($select);
	    $rows = $resultSet->toArray();
	    $list = array();
	    foreach ($rows as $row) {
	        $item = new  ExpressShippingFee();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	public function getNicks($shipment_id){
	    $adminOrderDetailTable = new AdminOrderDetailsTable($this->adapter);
	    
	    $nicks = $adminOrderDetailTable->getAllNickInShipment($shipment_id); 
	    return $nicks;
	}
	
	public function saveExpressShippingFee(ExpressShippingFee $expressshippingfee){
	    $data = array(
	        'shipment_id' =>  $expressshippingfee->getShipment_id(),
	        'admin' =>  $expressshippingfee->getAdmin(),
	        'nick' =>  $expressshippingfee->getNick(),
	        'fee'     => $expressshippingfee->getFee(),
	        'usd'     => $expressshippingfee->getUsd(),
	        'xrate'    => $expressshippingfee->getXrate(),
	        'note' =>  $expressshippingfee->getNote(),
	    );
	    
	    if ($expressshippingfee->getId()){
	        return $this->update($data,array('id' => $expressshippingfee->getId()));
	    }else{
	        $this->insert($data);
	        return $this->lastInsertValue;
	    }
	}
	 
	public function insertExpressShippingFee(ExpressShippingFee $expressshippingfee){
		$data = array(
            'shipment_id' =>  $expressshippingfee->getShipment_id(),
            'admin' =>  $expressshippingfee->getAdmin(),		     
            'nick' =>  $expressshippingfee->getNick(),
		    'fee'     => $expressshippingfee->getFee(),
		    'usd'     => $expressshippingfee->getUsd(),
		    'xrate'    => $expressshippingfee->getXrate(),
            'note' =>  $expressshippingfee->getNote(),      
        );
	 
		return $this->insert($data);
	}
	
	public function updateExpressShippingFee(ExpressShippingFee $expressshippingfee){
	    
	    $data = array(
	        'shipment_id' =>  $expressshippingfee->getShipment_id(),
	        'admin' =>  $expressshippingfee->getAdmin(),
	        'nick' =>  $expressshippingfee->getNick(),
	        'fee'     => $expressshippingfee->getFee(),
	        'usd'     => $expressshippingfee->getUsd(),
	        'xrate'    => $expressshippingfee->getXrate(),
	        'note' =>  $expressshippingfee->getNote(),
	    );
	    
	    return $this->update($data,array('id' => $expressshippingfee->getId()));
	}
	 
	
	public function deleteShippingFee($id){
	    return $this->delete(array('id' => $id));
	}
	
}