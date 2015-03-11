<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
 
use Zend\Db\Sql\Predicate\Expression;

 

class AdminOrderDetailsTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'order_details';
    public $adapter;

    function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }
    
    public function getStatusByNick($nick){
        $select = new Select($this->table);
        
        $select->columns(array(
        	'status' =>  new \Zend\Db\Sql\Expression('DISTINCT status')
        ))->where(array(
        	'nick' => $nick
        ));
        $resultSet = $this->selectWith($select);
        
        $rows =  $resultSet->toArray();
        $list = array();
        foreach($rows as $r){
            $list[] = $r['status']; 
        }
        
        
        
        return $list;
        
    }

    public function getTotalFinalByOrderno($orderno){
        $select = new Select($this->table);
        $select->columns(array(
        	'total'    => new \Zend\Db\Sql\Expression('sum(total_final)')
        ))->where(array(
        	'orderno'  => $orderno
        ));
        
        $resultSet = $this->selectWith($select);
        $result = $resultSet->current();
        
        return $result['total'];
        
    }
    
    public function getTotalServiceByOrderno($orderno){
    	$select = new Select($this->table);
    	$select->columns(array(
    			'total'    => new \Zend\Db\Sql\Expression('sum(service)')
    	))->where(array(
    			'orderno'  => $orderno
    	));
    
    	$resultSet = $this->selectWith($select);
    	$result = $resultSet->current();
    
    	return $result['total'];
    
    }
    
    
    public function getAllNickInShipment($shipment_id)
    {
        $subselect = new Select('shipment_order');
        $subselect->columns(array(
            'orderno' => 'orderno'
        )
        , true)->where(array(
            'shipment_id' => $shipment_id
        ));
        
        $select = new Select($this->table);
        $select->columns(array(
            'nick' => new Expression('DISTINCT nick '),
            'total_item' => new Expression('sum(items)')
        ), true)->where(array(
            new \Zend\Db\Sql\Predicate\In('orderno', $subselect)
        ));
        
        $select->group(array(
            'nick'
        ));
        $select->order(array(
            'nick' => 'ASC'
        ));
        
        $resultSet = $this->selectWith($select);
        $items = $resultSet->toArray();
        return $items;
	}
	
	public function getOrderDetailInShipment($shipment_id)
    {
        $subselect = new Select('shipment_order');
        $subselect->columns(array(
	    		'orderno' => 'orderno'
	    )
	    		, true)->where(array(
	    				'shipment_id' => $shipment_id
	    		));
		 
		$select = new Select($this->table);
		$select->columns(array(
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
				'status' => 'status',
				'checked'   => 'checked',
				'finish'    => 'finish'
		)
				, true)
				
	     ->where(array(
	         new \Zend\Db\Sql\Predicate\In('orderno', $subselect)
	     ));
		 
		$select->order(array('orderno'=>'DESC'));
		 
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrderDetails();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
        $select->columns(array(
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
            'status' => 'status',
            'checked'   => 'checked',
            'finish'    => 'finish'
        )
        , true)
	    		// ->from(array('u' =>'user'))
	  // -> join(array('t' => 'store'),"t.id=$this->table.store_id",array('store_name'    => 'name'))
	  ;
	  //  ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name'));
	    		//->where(array('user.id' => $id));
	    
	   
	    
	    $select->order(array('orderno'=>'DESC'));
	    
	    $resultSet = $this->selectWith($select);
	    $itemst = $resultSet->toArray();
	    $list = array();
	    foreach ($itemst as $row) {	        
	        $item = new AdminOrderDetails();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	public function getDetailsByOrderNo($orderno){
			
		$select = new Select($this->table);
		$select->columns(array(
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
				'status' => 'status',
		       'checked'   => 'checked',
		       'finish'    => 'finish'
		)
				, true)
				->join(array('o' => 'admin_order'),"o.orderno = $this->table.orderno",array('total_order'  => 'total_final'))
				->join(array('t'  => 'store'), "o.store_id = t.id", array('store_name' => 'name'))
				->join(array('so'  => 'shipment_order'), "o.orderno = so.orderno","shipment_id",'left')
				->join(array('s'  => 'shipment'), "s.id = so.shipment_id", array('shipment_name'  => 'ship_name'),'left')
				->where(array("$this->table.orderno" => $orderno));
	
		$select->order(array('o.datecreated'=>'DESC'));
	
			
	
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrderDetails();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	public function getDetailsByNick($nick,$shipment_id="",$status=""){
		 
		$select = new Select($this->table);
		$select->columns(array(
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
            'status' => 'status',
		    'checked'   => 'checked',
		    'finish'    => 'finish'
        )
        , true)
		->join(array('o' => 'admin_order'),"o.orderno = $this->table.orderno",array('total_order'  => 'total_final'))
		->join(array('t'  => 'store'), "o.store_id = t.id", array('store_name' => 'name'))
		->join(array('so'  => 'shipment_order'), "o.orderno = so.orderno","shipment_id",'left')
		->join(array('s'  => 'shipment'), "s.id = so.shipment_id", array('shipment_name'  => 'ship_name'),'left')
		->where(array('nick' => $nick));
		
		if ($shipment_id){
		    $select->where(array(
		    	's.id'   => $shipment_id
		    ));
		}
		
		if ($status){
		    $select->where(array(
		    	'status' => $status
		    ));
		}
		  
		$select->order(array('o.datecreated'=>'DESC'));

		
		 
		
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrderDetails();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	
	public function getOrderDetailsForChecking($orderno){
			
		$select = new Select($this->table);
		$select->columns(array(
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
				'status' => 'status',
		    'checked'   => 'checked',
		    'finish'    => 'finish'
		)
				, true)
				->join(array('ci' => 'cancelled_items'),"$this->table.id = ci.order_detail_id",array(
					'cancelled_items'  => 'items',
				    'cancelled_total'   => 'total'
				),"left")
				->join(array('ao' => 'admin_order'), "$this->table.orderno = ao.orderno", array())
				->join(array('s' => 'store'),"ao.store_id = s.id",array('store_name' => 'name'))				
				->join(array('so' => 'shipment_order'), "$this->table.orderno = so.orderno",array('package' => 'package'))
				->where(array($this->table.'.orderno'	=> $orderno));
			
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrderDetails();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	public function getOrderDetailsByOrderNoNotCancel($orderno){

	    $subselect = new Select('cancelled_items');
	    $subselect->columns(array(
	    		'order_detail_id' => 'order_detail_id'
	    ), true)
	    ->join(array('od'=> 'order_details'), "cancelled_items.order_detail_id = od.id",array())
	    ->where(array(
	    	'orderno' => $orderno
	    )); 
	    		
		$select = new Select($this->table);
		$select->columns(array(
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
				'status' => 'status',
				'checked'   => 'checked',
				'finish'    => 'finish'
		)
				, true)
				->where(array(
				    'orderno'	=> $orderno,
				    new \Zend\Db\Sql\Predicate\NotIn('id',$subselect)
				));
			
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrderDetails();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	public function getOrderDetailsByOrderNoItemCancelled($orderno){
	
		 
		$select = new Select($this->table);
		$select->columns(array(
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
				'status' => 'status',
				'checked'   => 'checked',
				'finish'    => 'finish'
		), true)
		->join(array('ci' => 'cancelled_items'),"$this->table.id = ci.order_detail_id",array(
			'cancelled_items'    => 'items',
		    'cancelled_total'     => 'total'
		))
		->where(array(
					'orderno'	=> $orderno,					 
		));
					
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrderDetails();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	public function getOrderDetailsByOrderNo($orderno){
		 
		$select = new Select($this->table);
		$select->columns(array(
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
            'status' => 'status',
		    'checked'   => 'checked',
		    'finish'    => 'finish'
        )
        , true)
		->where(array('orderno'	=> $orderno));
		 
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrderDetails();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	
	public function getOrderById($id){
		
		$select = new Select($this->table);
		$select->columns(array(
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
            'status' => 'status',
		    'checked'   => 'checked',
		    'finish'    => 'finish'
        )
        , true)
	   ->join(array('o' => 'admin_order'),"o.orderno = $this->table.orderno",array('total_order'  => 'total_final'))
	    ->join(array('t'  => 'store'), "o.store_id = t.id", array('store_name' => 'name'))
	    
	   /*  -> join(array('t' => 'usertype'),'t.id=user.usertype_id',
	    		         array('usertype_name'    => 'discription'))
	    ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name')) */
	    ->where(array($this->table.'.id' => $id));
	     
		 
	    $resultSet = $this->selectWith($select);
	 
		$row = (array)$resultSet->current();
		$item = new AdminOrderDetails();
		$item->setData($row);
		
		return $item;
	}
	
	
	

	public function addOrderDetail(AdminOrderDetails $order){
	    $data = array(
            'nick' => $order->getNick(),
            'orderno' => $order->getOrderno(),
            'orderdate' => $order->getOrderdate(),
            'description' => $order->getDescription(),
            'items' => $order->getItems(),
            'discount' => $order->getDiscount(),
            'ship_us' => $order->getShip_us(),
            'service' => $order->getService(),
            'tax' => $order->getTax(),
            'extra_fee' => $order->getExtra_fee(),
            'total_web' => $order->getTotal_web(),
            'total_web1' => $order->getTotal_web1(),
            'total_final' => $order->getTotal_final(),
            'status' => $order->getStatus(),
            'note' => $order->getNote(),
	        'checked'  => $order->getChecked(),
	        'finish'    => $order->getFinish()
        )
        ;
	 
	     $this->insert($data);
	     return $this->getLastInsertValue();
	    
	}
	
	public function updateOrderDetails(AdminOrderDetails $order){
	    
	    $id = $order->getId();
		$data = array(
            'nick' => $order->getNick(),
            'orderno' => $order->getOrderno(),
            'orderdate' => $order->getOrderdate(),
            'description' => $order->getDescription(),
            'items' => $order->getItems(),
            'discount' => $order->getDiscount(),
            'ship_us' => $order->getShip_us(),
            'service' => $order->getService(),
            'tax' => $order->getTax(),
            'extra_fee' => $order->getExtra_fee(),
            'total_web' => $order->getTotal_web(),
            'total_web1' => $order->getTotal_web1(),
            'total_final' => $order->getTotal_final(),
            'status' => $order->getStatus(),
            'note' => $order->getNote(),
		    'checked'  => $order->getChecked(),
		    'finish'    => $order->getFinish()
        );
		 
		return $this->update($data,array('id'=>$id));
		 
	}
	
	public function updateStatus($orderno,$status){ 	     
	    $update = new \Zend\Db\Sql\Update($this->table);
	    $update->set(array('status' => $status));
	    $update->where(array('orderno' => $orderno));
	    $this->updateWith($update);
	    
	}
	
	public function updateDetailStatus($id,$status){
		$update = new \Zend\Db\Sql\Update($this->table);
		$update->set(array('status' => $status));
		$update->where(array('id' => $id));
		$this->updateWith($update);
		 
	}
	
	public function deleteAllDetails($orderno){
		return $this->delete(array('orderno' => $orderno));
	}
	
	public function deleteOrderDetails($id){
	    return $this->delete(array('id' => $id));
	}
	
}