<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

 

class TongKetTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'tongket';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	 
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    	'shipment_id' => 'shipment_id',
	        'tongthu_kh'   => 'tongthu_kh',
	        'shipping_fee_kg'  => 'shipping_fee_kg',
	        'shipping_fee_usd' => 'shipping_fee_usd',
	        'linhtinh'         => 'linhtinh',
	        'tienhang'         => 'tienhang',
	        'tongshipping_kg'  => 'tongshipping_kg',
	        'tongshipping_usd' => 'tongshipping_usd',
	        'tongchiphi'       => 'tongchiphi',
	        'giaodichkhach'    => 'giaodichkhac',
	        'tongdung'         => 'tongdung',
	        'sum'              => 'sum',
	        'date'             => 'date',
	        'admin'            => 'admin'
	    ),true);
	  
	   
	    $resultSet = $this->selectWith($select);
	    $items = $resultSet->toArray();
	    $list = array();
	    foreach ($items as $row) {	        
	        $item = new TongKet();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	public function getTongketByShipmentId($shipment_id){
	   $select = new Select($this->table);
	   $select->columns(array(
	   	   'shipment_id' => 'shipment_id',
	        'tongthu_kh'   => 'tongthu_kh',
	        'shipping_fee_kg'  => 'shipping_fee_kg',
	        'shipping_fee_usd' => 'shipping_fee_usd',
	        'linhtinh'         => 'linhtinh',
	        'tienhang'         => 'tienhang',
	        'tongshipping_kg'  => 'tongshipping_kg',
	        'tongshipping_usd' => 'tongshipping_usd',
	        'tongchiphi'       => 'tongchiphi',
	        'giaodichkhach'    => 'giaodichkhac',
	        'tongdung'         => 'tongdung',
	        'sum'              => 'sum',
	        'date'             => 'date',
	        'admin'            => 'admin'
	   ))->where(array(
	   	   'shipment_id'   => $shipment_id
	   ));
	   $resultSet = $this->selectWith($select);
	   $row = $resultSet->current();
	   
	   if ($row){
	       
	       $tk = new TongKet();
	       $tk->setData($row);
	       
	       return $tk;
	   }else{
	       return false;
	   }
	   
	}
	
	
	public function getTongketByShipment($shipment_id){
		$select = new Select($this->table);
		$select->columns(array(
				'shipment_id' => 'shipment_id',
				'tongthu_kh'   => 'tongthu_kh',
				'shipping_fee_kg'  => 'shipping_fee_kg',
				'shipping_fee_usd' => 'shipping_fee_usd',
				'linhtinh'         => 'linhtinh',
				'tienhang'         => 'tienhang',
				'tongshipping_kg'  => 'tongshipping_kg',
				'tongshipping_usd' => 'tongshipping_usd',
				'tongchiphi'       => 'tongchiphi',
				'giaodichkhach'    => 'giaodichkhac',
				'tongdung'         => 'tongdung',
				'sum'              => 'sum',
				'date'             => 'date',
				'admin'            => 'admin'
		))->where(array(
				'shipment_id'   => $shipment_id
		));
		$resultSet = $this->selectWith($select);
		$row = $resultSet->current();
	
		$row = (array)$row;
		$tk = new TongKet();
		$tk->setData($row);
		
		return $tk;
		 
	
	}
	 
    public function saveTongKet(TongKet $tk)
    {
        $data = array(
           'shipment_id' => $tk->getShipment_id(),
	        'tongthu_kh'   => $tk->getTongthu_kh(),
	        'shipping_fee_kg'  => $tk->getShipping_fee_kg(),
	        'shipping_fee_usd' => $tk->getShipping_fee_usd(),
	        'linhtinh'         => $tk->getLinhtinh(),
	        'tienhang'         => $tk->getTienhang(),
	        'tongshipping_kg'  => $tk->getTongshipping_kg(),
	        'tongshipping_usd' => $tk->getTongshipping_usd(),
	        'tongchiphi'       => $tk->getTongchiphi(),
	        'giaodichkhac'    => $tk->getGiaodichkhac(),
	        'tongdung'         => $tk->getTongdung(),
	        'sum'              => $tk->getSum(),
	        'admin'            => $tk->getAdmin()
	    );
        
	      
	    if ($this->getTongketByShipmentId($tk->getShipment_id())){
	         return   $this->update($data,array('shipment_id' => $tk->getShipment_id() ));
        } else {
             return $this->insert($data);   
        }
          
	}
	
	
	
	public function deleteTongKet($shipment_id){
	    return $this->delete(array('shipment_id' => $shipment_id));
	}
	
}