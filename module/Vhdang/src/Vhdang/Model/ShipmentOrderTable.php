<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

 

class ShipmentOrderTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'shipment_order';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	 
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
					    		'shipment_id'	=> 'shipment_id',
					    		'orderno'	=> 'orderno',
	                            'package'     => 'package',
					    		'note'	=> 'note',
	                            'items'    => 'items',
                    	        'total_web'    => 'total_web',
                    	        'total_web1'   => 'total_web1',
                    	        'total_final',
					    		'checked'	=> 'checked',
					    		 				  		 
	    			),true)
	    		// ->from(array('u' =>'user'))
	   //-> join(array('t' => 'store'),"t.id=$this->table.store_id",array('store_name'    => 'name'))
	  ;
	  //  ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name'));
	    		//->where(array('user.id' => $id));
	    ;
	   
	    $select->order(array('orderno'=> 'DESC'));
	    
	     
	    $resultSet = $this->selectWith($select);
	    $items = $resultSet->toArray();
	    $list = array();
	    foreach ($items as $row) {	        
	        $item = new ShipmentOrder();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	 
	
	public function getShipmentOrder($shipment_id){
		$select = new Select($this->table);
		$select   -> columns(array(
				'shipment_id'	=> 'shipment_id',
				'orderno'	=> 'orderno',
		        'package'     => 'package',		             		     
				'note'	=> 'note',
    		    'items'    => 'items',
    		    'total_web'    => 'total_web',
    		    'total_web1'   => 'total_web1',
    		    'total_final',
				'checked'	=> 'checked',
		),true)
		->join(array('o' => 'admin_order'), "o.orderno = $this->table.orderno",array(
				'orderdate'	=> 'orderdate',
				'holder'	=> 'holder',
		        'creditcard'  => 'creditcard',
				'items_o'		=> 'items',
				'discount'	=> 'discount',
				'total_web_o'	=> 'total_web',
				'total_web1_o'	=> 'total_web1',
				'ship_us'	=> 'ship_us',
				'tax'		=> 'tax',
				'total_final_o'	=> 'total_final'
		))
		->join(array('s' => 'store'),"o.store_id = s.id",array('store_name' => 'name'))
		->where(array('shipment_id' => $shipment_id))
		->order(array('package'  => 'ASC','s.name'=> 'ASC','orderno' =>'orderno'));
		
		 
		$resultSet = $this->selectWith($select);
		$items = $resultSet->toArray();
		$list = array();
		foreach ($items as $row) {
			$item = new ShipmentOrder();
			$item->setData($row);
			$list[] = $item;
		}
		
		return $list;
		
	}
	
	public function getShipmentOrderByIds($shipment_id,$orderno){		
		$select = new Select($this->table);
		$select   -> columns(array(
	    		  	'shipment_id'	=> 'shipment_id',
		    		'orderno'	=> 'orderno',
		            'package'     => 'package',
		    		'note'	=> 'note',
        		    'items'    => 'items',
        		    'total_web'    => 'total_web',
        		    'total_web1'   => 'total_web1',
        		    'total_final',
		    		'checked'	=> 'checked', 	          
	    ),true)
	    
	   /*  -> join(array('t' => 'usertype'),'t.id=user.usertype_id',
	    		         array('usertype_name'    => 'discription'))
	    ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name')) */
	    ->where(array('shipment_id' => $shipment_id, 'orderno' => $orderno));
	     
		 
	    $resultSet = $this->selectWith($select);
	 
		$row = (array)$resultSet->current();
		$item = new ShipmentOrder();
		$item->setData($row);		
		return $item;
	}
	
	public function addShipmentOrder(ShipmentOrder $shipmentorder){
		$data = array(
				'shipment_id'	=> $shipmentorder->getShipment_id(),
				'orderno'	=>  $shipmentorder->getOrderno(),
		        'package' => $shipmentorder->getPackage(),
				'note'	=> $shipmentorder->getNote(),
    		    'items'    => $shipmentorder->getItems(),
    		    'total_web'    => $shipmentorder->getTotal_web(),
    		    'total_web1'   => $shipmentorder->getTotal_web1(),
    		    'total_final' => $shipmentorder->getTotal_final(),
				'checked'	=> $shipmentorder->getChecked(),
		);
		return  $this->insert($data);
	}

    public function saveShipmentOrder(ShipmentOrder $shipmentorder)
    {
        $data = array(
            'shipment_id' => $shipmentorder->getShipment_id(),
            'orderno' => $shipmentorder->getOrderno(),
            'package' => $shipmentorder->getPackage(),
            'note' => $shipmentorder->getNote(),
            'items' => $shipmentorder->getItems(),
            'total_web' => $shipmentorder->getTotal_web(),
            'total_web1' => $shipmentorder->getTotal_web1(),
            'total_final' => $shipmentorder->getTotal_final(),
    		'checked'	=> $shipmentorder->getChecked(), 	 
	    );
        
	    $shipment_id = $shipmentorder->getShipment_id();
	    $orderno = $shipmentorder->getOrderno();
	     
	        if ($this->getShipmentOrderByIds($shipment_id, $orderno)){
	            $this->update($data,array('shipment_id' => $shipment_id,'orderno' => $orderno ));
            } else {
                throw new \Exception("Update failed: ShipmentOrder  id $shipment_id does not exist.");
            }
         return 1;
	}
	
	
	
	public function deleteShipmentOrder($id,$orderno){
	    return $this->delete(array('shipment_id' => $id,'orderno' => $orderno));
	}
	
}