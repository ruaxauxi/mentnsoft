<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\Expression;
use Zend\Db\Sql\Sql;

 

class ShippingWeightTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'shipping_weight';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	/* protected $_orderdetail_id;
	protected $_shipment_id;
	protected $_weight;
	protected $_date;
	protected $_note;
	
	protected $_nick;
	protected $_description;
	protected $_items;
	protected $_orderno; */
	
	/** @abstract lay total shipping fee
	 * @param unknown $shipment_id
	 * @return Ambigous <>
	 */
	public function getTotalShippingWeightByShipmentId($shipment_id){
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		 'total' => new \Zend\Db\Sql\Expression('sum(total)')
	    ),true)
	    ->where(array(
	    	'shipment_id' => $shipment_id
	    ));
	    
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->current();
	    return $result['total'];
	    
	}
	
	
	/**
	 * @abstract tra ve tong khoi luong (weight) theo dot
	 * @param unknown $shipment_id
	 * @return Ambigous <>
	 */
	public function getTotaWeightByShipmentId($shipment_id){
		$select = new Select($this->table);
		$select   -> columns(array(
				'total' => new \Zend\Db\Sql\Expression('sum(weight)')
		),true)
		->where(array(
				'shipment_id' => $shipment_id
		));
		 
		$resultSet = $this->selectWith($select);
		$result = $resultSet->current();
		return $result['total'];
		 
	}
	
	public function getShipmentItemList($shipment_id){		
 		
	    $orderDetailTable = new AdminOrderDetailsTable($this->adapter);
	    $nicks = $orderDetailTable->getAllNickInShipment($shipment_id);
		 
		$list = array();
		foreach ($nicks as $item){
		    $shipmentweight = $this->getShippingWeightByKey($shipment_id, $item['nick']);
		    if (!$shipmentweight){
		        $shipmentweight = new ShippingWeight();
		    }
		    $shipmentweight->setNick($item['nick']);
		    $shipmentweight->setTotal_item($item['total_item']);
		    $list[]    = $shipmentweight;
		}
		 
		return $list;
	}
	
	public function deleteInvalidShippingWeight($shipment_id){
        return $this->delete(array(
            'shipment_id' => $shipment_id,
            new Expression('weight = 0'),
        ));
	}
	
 
	/* public function getUnshipped(){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'	=> 'id',
				'ship_date'	=> 'ship_date',
				'ship_name'	=> 'ship_name',
				'weight'	=> 'weight',
				'note'		=> 'note',
				'finish'	=> 'finish'
		),true)
		// ->from(array('u' =>'user'))
		//-> join(array('t' => 'store'),"t.id=$this->table.store_id",array('store_name'    => 'name'))
		;
		//  ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name'));
		//->where(array('user.id' => $id));
	
		 
			$select->where(array('finish' => 0));
		 
	
		$select->order(array('ship_date'=> 'DESC'));
		 
	
		$resultSet = $this->selectWith($select);
		$items = $resultSet->toArray();
		$list = array();
		foreach ($items as $row) {
			$item = new Shipment();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	 */
	 
	public function getShippingWeightByShippmentId($shipment_id){
	
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
		->where(array('shipment_id' => $shipment_id));
	
			
		$resultSet = $this->selectWith($select);
	
		$result = $resultSet->toArray();
		
		$list = array();
		foreach($result as $row){
		    $item = new ShippingWeight();
		    $row = (array)$row;
		    $item->setData($row);
		    
		    $list[] = $item;
		}

		return $list;
		 
	
	}
	
	
	 
	
	
	public function getShippingWeightByKey($shipment_id,$nick){
		
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
		
	}
	
	public function insertShippingWeight(ShippingWeight $shippingweight){
		$data = array(
            'nick' => $shippingweight->getNick(),
            'shipment_id' => $shippingweight->getShipment_id(),
            'date' => $shippingweight->getDate(),
            'note' => $shippingweight->getNote(),
            'weight' => $shippingweight->getWeight(),
            'price' => $shippingweight->getPrice(),
            'total' => $shippingweight->getTotal()
        );
	 
		return $this->insert($data);
	}
	
	
	public function saveShippingWeight(ShippingWeight $shippingweight){
       
        if ($this->getShippingWeightByKey($shippingweight->getShipment_id(), $shippingweight->getNick())) {
            $data = array(
            		'date' => $shippingweight->getDate(),
            		'note' => $shippingweight->getNote(),
            		'weight' => $shippingweight->getWeight(),
            		'price' => $shippingweight->getPrice(),
            		'total' => $shippingweight->getTotal()
            );
            
            return $this->update($data, array(
                'shipment_id' => $shippingweight->getShipment_id(),
                'nick' => $shippingweight->getNick()
            ));
        } else {
            $data = array(
            		'nick' => $shippingweight->getNick(),
            		'shipment_id' => $shippingweight->getShipment_id(),
            		'date' => $shippingweight->getDate(),
            		'note' => $shippingweight->getNote(),
            		'weight' => $shippingweight->getWeight(),
            		'price' => $shippingweight->getPrice(),
            		'total' => $shippingweight->getTotal()
            );
            return $this->insert($data);
        }
        return false;
    }
	
	
	public function deleteShippingWeight($shipment_id,$nick){
	    return $this->delete(array('shipment_id' => $shipment_id,'nick' => $nick));
	}
	
}