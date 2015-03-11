<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\In;

 

class ShipmentTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'shipment';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function getLoTamTinh($shipment_id,$nick){
		$select = new Select($this->table);
		$select->columns(array(
	
		))->join(array('so' => 'shipment_order'),"so.shipment_id = $this->table.id", array())
		->join(array('od' => 'order_details'), "od.orderno = so.orderno",
				array(
						'total' => new Expression('sum(od.total_final)')
				))
				->where(array(
						"$this->table.id" => $shipment_id,
				        'od.nick'   => $nick
				));
				;
				   
				$resultSet = $this->selectWith($select);
				$result = $resultSet->current();
				return $result['total'];
				 
	}
	
	/**
	 * 
	 * @abstract lay ta ca cac dot ma nick co mua hang
	 * @param unknown $nick
	 */
	public function getShipmentListByNick($nick){
	    $select = new Select($this->table);
	    $subselect = new Select('shipment_order');
	    $subselect->columns(array(
	    		 new Expression('DISTINCT shipment_id')
	    ))
	    ->join(array('od' => 'order_details'), "od.orderno = shipment_order.orderno",array())
	    ->where(array(
	    	'nick'    => $nick
	    ));
	    
	    
	    $select->columns(array(
	        'id'	=> 'id',
	        'ship_date'	=> 'ship_date',
	        'ship_name'	=> 'ship_name',
	        'weight'	=> 'weight',
	        'note'		=> 'note',
	        'finish'	=> 'finish',
	        'delivered'  => 'delivered',
	        'checked' => 'checked',
	        'finalized'   => 'finalized',
	        'paid'        => 'paid'
	    ))	    
	    ->where(array(
	    	new In('id',$subselect)
	    ))
	    ->order(array(
	    	'id' => 'DESC'
	    ))
	    ;
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
	
	
	public function getListLoTamTinh($shipment_id,$nick){
		$select = new Select($this->table);
		$select->columns(array(
	           
		))->join(array('so' => 'shipment_order'),"so.shipment_id = $this->table.id", array())
		->join(array('od' => 'order_details'), "od.orderno = so.orderno",
				array(
				    'id' => 'id',
				    'nick' => 'nick',
				    'orderno' => 'orderno',
				    'orderdate' => 'orderdate',
				    'description' => 'description',
				    'items' => 'items',
				    'discount' => 'discount',
				    'ship_us' => 'ship_us',
				    'service' => 'service',
				    'tax' => 'tax',
				    'extra_fee' => 'extra_fee',
				    'total_web' => 'total_web',
				    'total_web1' => 'total_web1',
				    'total_final' => 'total_final',
				    'note' => 'note',
				))
				->where(array(
						"$this->table.id" => $shipment_id,
						 
				));
				
		$select->where->in('od.nick',$nick);
				 
				
		$resultSet = $this->selectWith($select);
		$result = $resultSet->toArray();
	    
		$list = array();
		foreach($result as $row){
		    $item = new AdminOrderDetails();
		    $item->setData($row);
		    
		    $list[] = $item;
		}
		
		return $list;
					
	}
	
	
	/*
	 * Lay tong thu cua cac order cua  kh theo dot
	 * 
	 *   
	 * 
	 * */
	public function getTongThuOrdersDetails($shipment_id){
	    $select = new Select($this->table);
	    $select->columns(array(
	    	
	    ))->join(array('so' => 'shipment_order'),"so.shipment_id = $this->table.id", array())
	    ->join(array('od' => 'order_details'), "od.orderno = so.orderno",
	        array(
	    	  'total' => new Expression('sum(od.total_final)')
	        ))
	    ->where(array(
	    	"$this->table.id" => $shipment_id
	    ));
	    ;
	    
	    //  called shipment id
	   /*  $canelled_shipment_id = 18;
	    
	    $select->where(array(
	    		new PredicateSet(array(
	    				new Operator("$this->table.finish",  Operator::OPERATOR_EQUAL_TO, 1),
	    				new Operator("$this->table.id",  Operator::OPERATOR_NOT_EQUAL_TO, $canelled_shipment_id),
	    		),PredicateSet::COMBINED_BY_OR)
	    )); */
	    
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->current();
	    return $result['total'];
	    
	}
	
	
	public function getShipmentNotPaid(){
	
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'	=> 'id',
				'ship_date'	=> 'ship_date',
				'ship_name'	=> 'ship_name',
				'weight'	=> 'weight',
				'note'		=> 'note',
				'finish'	=> 'finish',
				'delivered'  => 'delivered',
				'checked' => 'checked',
				'finalized'   => 'finalized',
				'paid'        => 'paid'
		),true);
	
		$select->where(array('paid' => 0));
		 
	
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
	
	
	public function getShipnentcomplete(){
	   
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'	=> 'id',
				'ship_date'	=> 'ship_date',
				'ship_name'	=> 'ship_name',
				'weight'	=> 'weight',
				'note'		=> 'note',
				'finish'	=> 'finish',
		         'delivered'  => 'delivered',
		    'checked' => 'checked',
		    'finalized'   => 'finalized',
		    'paid'        => 'paid'
		),true);
		 			
		$select->where(array('finish' => 1,'delivered' => 0));				
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

    public function getshippedOrders()
    {
    	 
    	 
    	$select = new Select($this->table);
    	$select   -> columns(array(
    			'id'	=> 'id',
    			'ship_date'	=> 'ship_date',
    			'ship_name'	=> 'ship_name',
    			'weight'	=> 'weight',
    			'note'		=> 'note',
    			'finish'	=> 'finish',
    			'delivered'  => 'delivered',
    			'checked' => 'checked',
    			'finalized'   => 'finalized',
    	       'paid'        => 'paid'
    	),true);
    	
        //  called shipment id
    	$canelled_shipment_id = 18;
    
    	$select->where(array(
    			new PredicateSet(array(
    					new Operator('finish',  Operator::OPERATOR_EQUAL_TO, 1),
    					new Operator('id',  Operator::OPERATOR_NOT_EQUAL_TO, $canelled_shipment_id),
    			),PredicateSet::COMBINED_BY_OR)
    	));
    
    
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
    
    public function getUnshipped()
    {
       
	    
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'	=> 'id',
				'ship_date'	=> 'ship_date',
				'ship_name'	=> 'ship_name',
				'weight'	=> 'weight',
				'note'		=> 'note',
				'finish'	=> 'finish',
		    'delivered'  => 'delivered',
		    'checked' => 'checked',
		    'finalized'   => 'finalized',
		    'paid'        => 'paid'
		),true);
		 
		/* $select->where(array('finish' => 0));
		$select->where->or(array());
		 */
		
		$canelled_shipment_id = 18;
		
		$select->where(array(
				new PredicateSet(array(
						new Operator('finish',  Operator::OPERATOR_EQUAL_TO, 0),
						new Operator('id',  Operator::OPERATOR_EQUAL_TO, $canelled_shipment_id),
				),PredicateSet::COMBINED_BY_OR)
		));
		
	
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
	
	public function fetchAll($finish = null){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
					    		'id'	=> 'id',
					    		'ship_date'	=> 'ship_date',
					    		'ship_name'	=> 'ship_name',
					    		'weight'	=> 'weight',
					    		'note'		=> 'note',
					    		'finish'	=> 'finish'  	,
	        'delivered'  => 'delivered',
	        'checked' => 'checked',
	        'finalized'   => 'finalized',
	        'paid'        => 'paid'
	    			),true)
	    		// ->from(array('u' =>'user'))
	   //-> join(array('t' => 'store'),"t.id=$this->table.store_id",array('store_name'    => 'name'))
	  ;
	  //  ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name'));
	    		//->where(array('user.id' => $id));
	   
	    if ($finish){
	    	$select->where(array('finish' => $finish));
	    }
	   
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
	
	
	public function getShipmentById($id){
		
		$select = new Select($this->table);
		$select   -> columns(array(
	    		  	'id'	=> 'id',
		    		'ship_date'	=> 'ship_date',
		    		'ship_name'	=> 'ship_name',
		    		'weight'	=> 'weight',
		    		'note'		=> 'note',
		    		'finish'	=> 'finish'  ,
		    'delivered'  => 'delivered',
		    'checked' => 'checked',
		    'finalized'   => 'finalized',
		    'paid'        => 'paid'
	          
	    ),true)
	    
	   /*  -> join(array('t' => 'usertype'),'t.id=user.usertype_id',
	    		         array('usertype_name'    => 'discription'))
	    ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name')) */
	    ->where(array('id' => $id));
	     
		 
	    $resultSet = $this->selectWith($select);
		
		$row = (array)$resultSet->current();
		$item = new Shipment();
		$item->setData($row);		
		return $item;
	}
	
 
	
	public function saveShipment(Shipment $shipment){
	  $data = array(
	  		'ship_date'	=> $shipment->getShip_date(),
	  		'ship_name'	=> $shipment->getShip_name(),
	  		'weight'	=> $shipment->getWeight(),
	  		'note'		=> $shipment->getNote(),
	  		'finish'	=> $shipment->getFinish(),
	      'delivered'  => $shipment->getDelivered(),
	      'checked'    => $shipment->getChecked(),
	      'finalized'  => $shipment->getFinalized(),
	      'paid'        => $shipment->getPaid()
	    );
	    
	    
	    $id = $shipment->getId();
	    
	    if(!$id){	        
	        $this->insert($data);
	        $shipment->setId($this->getLastInsertValue());//update last inserted id
	    }else{
	        if ($this->getShipmentById($id)){
	            return 	$this->update($data,array('id' => $id));
            } else {
                throw new \Exception("Update failed: Shipment id $id does not exist.");
            }
        }
      
        return  $shipment->getId();
	}
	
	
	
	public function deleteShipment($id){
	    return $this->delete(array('id' => $id));
	}
	
}