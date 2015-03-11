<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Predicate\Like;

 

class AdminOrderTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'admin_order';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll($admin = null,$store_id=null){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		    'orderno'          =>  'orderno',
                    'admin'            => 'admin',
                    'store_id'         =>  'store_id',
                    'orderdate'        =>   'orderdate',
                    'items'            => 'items',
                    'total_web'        => 'total_web',
                    'discount'         => 'discount',
                    'total_web1'       => 'total_web1',
                    'tax'              => 'tax',
                    'ship_us'          => 'ship_us',
                    'total_final'      => 'total_final',
                    'creditcard'      => 'creditcard',
                    'holder'           => 'holder',
                    'datecreated'      => 'datecreated',
                    'lastupdated'      => 'lastupdated', 
	                'description'       => 'description',                   
                    'valid'            => 'valid'    	,
	        'checked'  => 'checked'  ,
	        'finalized'   => 'finalized'
	    ),true)
	    		// ->from(array('u' =>'user'))
	   -> join(array('t' => 'store'),"t.id=$this->table.store_id",array('store_name'    => 'name'))
	  ;
	  //  ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name'));
	    		//->where(array('user.id' => $id));
	    
	   if ($admin){
	   		$select->where(array('admin'   => $admin));
	   }
	   
	    
	    if ($store_id){
	        $select->where(array('store_id'=> $store_id));
	    }
	    $select->order(array('lastupdated'=>'DESC'));
	    
	    $resultSet = $this->selectWith($select);
	    $itemst = $resultSet->toArray();
	    $list = array();
	    foreach ($itemst as $row) {	        
	        $item = new AdminOrder();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	public function fetchAllForPaging($admin = null,$store_id=null,$order_search=null){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'orderno'          =>  'orderno',
				'admin'            => 'admin',
				'store_id'         =>  'store_id',
				'orderdate'        =>   'orderdate',
				'items'            => 'items',
				'total_web'        => 'total_web',
				'discount'         => 'discount',
				'total_web1'       => 'total_web1',
				'tax'              => 'tax',
				'ship_us'          => 'ship_us',
				'total_final'      => 'total_final',
				'creditcard'      => 'creditcard',
				'holder'           => 'holder',
				'datecreated'      => 'datecreated',
				'lastupdated'      => 'lastupdated',
				'description'       => 'description',
				'valid'            => 'valid'    	,
				'checked'  => 'checked'  ,
				'finalized'   => 'finalized'
		),true)
		// ->from(array('u' =>'user'))
		-> join(array('t' => 'store'),"t.id=$this->table.store_id",array('store_name'    => 'name'))
		;
		//  ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name'));
		//->where(array('user.id' => $id));
		 
		if ($admin){
			$select->where(array('admin'   => $admin));
		}
	
		 
		if ($store_id){
			$select->where(array('store_id'=> $store_id));
		}
		
		if ($order_search){
		    $order_search = '%'.$order_search.'%';
		    $select->where(array(
		         new Like('orderno',$order_search)
		    ));
		}
		
		$select->order(array('lastupdated'=>'DESC'));
		 
		/* $resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrder();
			$item->setData($row);
			$list[] = $item;
		}
		return $list; */
		
		$resultSetPrototype = new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new AdminOrder());
		// create a new pagination adapter object
		$paginatorAdapter = new DbSelect(
				// our configured select object
				$select,
				// the adapter to run it against
				$this->getAdapter(),
				// the result set to hydrate
				$resultSetPrototype
		);
		$paginator = new Paginator($paginatorAdapter);
		
		return $paginator;
		
	}
	
	/**
	 * 
	 * @abstract lay tat cac cac order trong cac dot chua thanh toan
	 * 
	 */
	public function getOrdersInShipmentNotPaid(){
	    
	    $subselect = new Select('shipment_order');
	    $subselect->columns(array(
	    		'orderno' => 'orderno'
	    ), true)
	    ->join(array('s'=>'shipment'), "shipment_order.shipment_id = s.id",array())
	    ->where(array(
	    		's.paid' => 0
	    ));
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'orderno'          =>  'orderno',
	    		'admin'            => 'admin',
	    		'store_id'         =>  'store_id',
	    		'orderdate'        =>   'orderdate',
	    		'items'            => 'items',
	    		'total_web'        => 'total_web',
	    		'discount'         => 'discount',
	    		'total_web1'       => 'total_web1',
	    		'tax'              => 'tax',
	    		'ship_us'          => 'ship_us',
	    		'total_final'      => 'total_final',
	    		'creditcard'      => 'creditcard',
	    		'holder'           => 'holder',
	    		'datecreated'      => 'datecreated',
	    		'lastupdated'      => 'lastupdated',
	    		'description'       => 'description',
	    		'valid'            => 'valid'    	,
	    		'checked'  => 'checked'  ,
	    		'finalized'   => 'finalized'
	    ),true)
	    ->where(array(
	        new \Zend\Db\Sql\Predicate\In('orderno', $subselect)
	    ));
	    
	    $resultSet = $this->selectWith($select);
	    $itemst = $resultSet->toArray();
	    $list = array();
	    foreach ($itemst as $row) {
	    	$item = new AdminOrder();
	    	$item->setData($row);
	    	$list[] = $item;
	    }
	    return $list;
	    
	}
	
	public function getTotalFinalByShipmentID($shipment_id){
		$subselect = new Select('shipment_order');
		$subselect->columns(array(
				'orderno' => 'orderno'
		), true)
		->where(array(
				'shipment_id' => $shipment_id
		));
	
		$select = new Select($this->table);
		$select->columns(array(
				'total_final' => new Expression('sum(total_final)')
		), true)->where(array(
				new \Zend\Db\Sql\Predicate\In('orderno', $subselect)
		));
	
		 
		$resultSet = $this->selectWith($select);
		$result = $resultSet->current();
		 
		return $result['total_final'];
		 
	}
	
	public function getTotalWeb1ByShipmentID($shipment_id){
	    $subselect = new Select('shipment_order');
	    $subselect->columns(array(
	    		'orderno' => 'orderno'
	    ), true)
	    ->where(array(
	    				'shipment_id' => $shipment_id
	    ));

	    $select = new Select($this->table);
	    $select->columns(array(	    		
	    		'total_web1' => new Expression('sum(total_web1)')
	    ), true)->where(array(
	    		new \Zend\Db\Sql\Predicate\In('orderno', $subselect)
	    ));
	    	
	    
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->current();
	    
	    return $result['total_web1'];
	    
	}
	
	public function getUncheck($shipment_id){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'orderno'          =>  'orderno',
				'admin'            => 'admin',
				'store_id'         =>  'store_id',
				'orderdate'        =>   'orderdate',
				'items'            => 'items',
				'total_web'        => 'total_web',
				'discount'         => 'discount',
				'total_web1'       => 'total_web1',
				'tax'              => 'tax',
				'ship_us'          => 'ship_us',
				'total_final'      => 'total_final',
				'creditcard'      => 'creditcard',
				'holder'           => 'holder',
				'datecreated'      => 'datecreated',
				'lastupdated'      => 'lastupdated',
				'description'       => 'description',
				'valid'            => 'valid',
		    'checked'  => 'checked',
		    'finalized'   => 'finalized'
		),true)		
		-> join(array('t' => 'store'),"t.id=$this->table.store_id",array('store_name'    => 'name'))
		->join(array('so' => 'shipment_order'),"$this->table.orderno = so.orderno",array())
		->where(array('so.shipment_id' => $shipment_id, 'so.checked' => 0, "$this->table.checked" => 0))
		;		
		$select->order(array('orderno'=>'DESC'));
		 
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrder();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	public function getOrderNotShippedByStoreDescription($store_id = null,$description = null){
		$subselect = new Select('shipment_order');
		$subselect->columns(array(
				'orderno'          =>  'orderno',
		),true);
	
	
		$select = new Select($this->table);
		$select   -> columns(array(
				'orderno'          =>  'orderno',
				'admin'            => 'admin',
				'store_id'         =>  'store_id',
				'orderdate'        =>   'orderdate',
				'items'            => 'items',
				'total_web'        => 'total_web',
				'discount'         => 'discount',
				'total_web1'       => 'total_web1',
				'tax'              => 'tax',
				'ship_us'          => 'ship_us',
				'total_final'      => 'total_final',
				'creditcard'      => 'creditcard',
				'holder'           => 'holder',
				'datecreated'      => 'datecreated',
				'lastupdated'      => 'lastupdated',
				'description'       => 'description',
				'valid'            => 'valid',
		    'checked'  => 'checked',
		    'finalized'   => 'finalized'
	
		),true)
		->join(array('s' => 'store'),"s.id = $this->table.store_id",array('store_name'=> 'name'))
		->where(
				new \Zend\Db\Sql\Predicate\NotIn('orderno',$subselect)
		);
		
		if ($store_id){
		    $select->where(array('store_id' => $store_id));
		}
		
		if ($description){
		    $select->where->like('description', '%' . $description . '%');
		}
			
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrder();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	/**
	 * @abstract Get all Orders in a shipment
	 * @param unknown $shipment_id
	 * @return multitype:\Vhdang\Model\AdminOrder 
	 */
	public function getOrderInShipment($shipment_id){
		$subselect = new Select('shipment_order');
		$subselect->columns(array(
				'orderno'          =>  'orderno',
		),true)
		->where(array(
			'shipment_id'    => $shipment_id
		));
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'orderno'          =>  'orderno',
				'admin'            => 'admin',
				'store_id'         =>  'store_id',
				'orderdate'        =>   'orderdate',
				'items'            => 'items',
				'total_web'        => 'total_web',
				'discount'         => 'discount',
				'total_web1'       => 'total_web1',
				'tax'              => 'tax',
				'ship_us'          => 'ship_us',
				'total_final'      => 'total_final',
				'creditcard'      => 'creditcard',
				'holder'           => 'holder',
				'datecreated'      => 'datecreated',
				'lastupdated'      => 'lastupdated',
				'description'       => 'description',
				'valid'            => 'valid',
				'checked'  => 'checked',
				'finalized'   => 'finalized'
	
		),true)
		->join(array('s'=>'store'), "$this->table.store_id = s.id",array(
			'store_name' => 'name'
		))
		->where(
				array(
						new \Zend\Db\Sql\Predicate\In('orderno',$subselect),
	
				));
	
		 
		$select->order(array('orderdate' => 'ASC'));
	
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrder();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	
	/**
	 * 
	 * @abstract lay tat ca cac order co ngay order > $week nhung chua duoc add vao dot nao
	 * 
	 * @param unknown $week
	 * @return multitype:\Vhdang\Model\AdminOrder 
	 */
	public function getOrderNotInShipment($week){
		$subselect = new Select('shipment_order');
		$subselect->columns(array(
				'orderno'          =>  'orderno',
		),true);
	    $interval = "P".($week*7)."D";
	    $date = new \DateTime();
	    $date->sub(new \DateInterval($interval));
		$select = new Select($this->table);
		$select   -> columns(array(
				'orderno'          =>  'orderno',
				'admin'            => 'admin',
				'store_id'         =>  'store_id',
				'orderdate'        =>   'orderdate',
				'items'            => 'items',
				'total_web'        => 'total_web',
				'discount'         => 'discount',
				'total_web1'       => 'total_web1',
				'tax'              => 'tax',
				'ship_us'          => 'ship_us',
				'total_final'      => 'total_final',
				'creditcard'      => 'creditcard',
				'holder'           => 'holder',
				'datecreated'      => 'datecreated',
				'lastupdated'      => 'lastupdated',
				'description'       => 'description',
				'valid'            => 'valid',
				'checked'  => 'checked',
				'finalized'   => 'finalized'
	
		),true)		
		->where(
				array(
				    new \Zend\Db\Sql\Predicate\NotIn('orderno',$subselect),
				    
		));
	   
		 $select->where->lessThanOrEqualTo('orderdate', $date->format("Y-m-d"));
		 $select->order(array('orderdate' => 'ASC'));
        	   
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrder();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	
	public function getOrderNotShipped(){
		$subselect = new Select('shipment_order');
		$subselect->columns(array(
				'orderno'          =>  'orderno',
		),true);
		
		
		$select = new Select($this->table);
		$select   -> columns(array(
				'orderno'          =>  'orderno',
				'admin'            => 'admin',
				'store_id'         =>  'store_id',
				'orderdate'        =>   'orderdate',
				'items'            => 'items',
				'total_web'        => 'total_web',
				'discount'         => 'discount',
				'total_web1'       => 'total_web1',
				'tax'              => 'tax',
				'ship_us'          => 'ship_us',
				'total_final'      => 'total_final',
				'creditcard'      => 'creditcard',
				'holder'           => 'holder',
				'datecreated'      => 'datecreated',
				'lastupdated'      => 'lastupdated',
		          'description'       => 'description',
				'valid'            => 'valid',
		    'checked'  => 'checked',
		    'finalized'   => 'finalized'
	     
		),true)
		->where(
				new \Zend\Db\Sql\Predicate\NotIn('orderno',$subselect)
			)
		;
		 
		$resultSet = $this->selectWith($select);
		$itemst = $resultSet->toArray();
		$list = array();
		foreach ($itemst as $row) {
			$item = new AdminOrder();
			$item->setData($row);
			$list[] = $item;
		}
		return $list;
	}
	

	
	public function getOrderByNo($orderno,$admin = null){
		
		$select = new Select($this->table);
		$select   -> columns(array(
	    		    'orderno'          =>  'orderno',
                    'admin'            => 'admin',
                    'store_id'         =>  'store_id',
                    'orderdate'        =>   'orderdate',
                    'items'            => 'items',
                    'total_web'        => 'total_web',
                    'discount'         => 'discount',
                    'total_web1'       => 'total_web1',
                    'tax'              => 'tax',
                    'ship_us'          => 'ship_us',
                    'total_final'      => 'total_final',
                    'creditcard'      => 'creditcard',
                    'holder'           => 'holder',
                    'datecreated'      => 'datecreated',
                    'lastupdated'      => 'lastupdated',  
		            'description'       => 'description',
                    'valid'            => 'valid'   ,
		    'checked'  => 'checked',
		    'finalized'   => 'finalized'
	          
	    ),true)
	    -> join(array('t' => 'store'),"t.id=$this->table.store_id",array('store_name'    => 'name'))
	   /*  -> join(array('t' => 'usertype'),'t.id=user.usertype_id',
	    		         array('usertype_name'    => 'discription'))
	    ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name')) */
	    ->where(array('orderno' => $orderno));
	     
		if ($admin){
		    $select->where(array('admin'=>$admin));
		}
	    $resultSet = $this->selectWith($select);
	 
		$row = (array)$resultSet->current();
		$item = new AdminOrder();
		$item->setData($row);
		
		return $item;
	}
	
	public function getOrderByNick($nick,$status){	
		$select = new Select($this->table);
		$select   -> columns(array(
				'orderno'          =>  'orderno',
				'admin'            => 'admin',
				'store_id'         =>  'store_id',
				'orderdate'        =>   'orderdate',
				'items'            => 'items',
				'total_web'        => 'total_web',
				'discount'         => 'discount',
				'total_web1'       => 'total_web1',
				'tax'              => 'tax',
				'ship_us'          => 'ship_us',
				'total_final'      => 'total_final',
				'creditcard'      => 'creditcard',
				'holder'           => 'holder',
				'datecreated'      => 'datecreated',
				'lastupdated'      => 'lastupdated',	
		        'description'       => 'description',
				'valid'            => 'valid',
		    'checked'  => 'checked',
		    'finalized'   => 'finalized'
	     
		),true)
	
		->where(array('admin' => $nick,'status'=> $status));
		 
		$resultSet = $this->selectWith($select);
		$items = $resultSet->toArray();
	    $list = array();
	    foreach ($items as $row) {	        
	        $item = new AdminOrder();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	
	public function isValidOrderNo($orderno){
	    $row = $this->select(array('orderno' => $orderno))->current();
	    if (!$row){
	    	return true;
	    }else{
	    	return false;
	    }
	}
	
	public function addOrder(AdminOrder $order){
	    $data = array(
	        'orderno'          => $order->getOrderno(),
	        'admin'            => $order->getAdmin(),
	        'store_id'         => $order->getStore_id(),
	        'orderdate'      => $order->getOrderdate(),
	        'items'            => $order->getItems(),
	        'total_web'        => $order->getTotal_web(),
	        'discount'         => $order->getDiscount(),
	        'total_web1'       => $order->getTotal_web1(),
	        'tax'              => $order->getTax(),
	        'ship_us'          => $order->getShip_us(),
	        'total_final'      => $order->getTotal_final(),
	        'creditcard'      => $order->getCreditcard(),
	        'holder'           => $order->getHolder(),
	        'lastupdated'      => $order->getLastupdated(),	
	        'description'       => $order->getDescription(),
	        'valid'            => $order->getValid(),
	        'checked'          => $order->getChecked(),
	        'finalized'        => $order->getFinalized()
	    );
	    
	    return $this->insert($data);
	    
	}
	
	public function updateOrder(AdminOrder $order){
	    
	    $orderno = $order->getOrderno();
		$data = array(
			 
				'admin'            => $order->getAdmin(),
				'store_id'         => $order->getStore_id(),
				'orderdate'      => $order->getOrderdate(),
				'items'            => $order->getItems(),
				'total_web'        => $order->getTotal_web(),
				'discount'         => $order->getDiscount(),
				'total_web1'       => $order->getTotal_web1(),
				'tax'              => $order->getTax(),
				'ship_us'          => $order->getShip_us(),
				'total_final'      => $order->getTotal_final(),
				'creditcard'      => $order->getCreditcard(),
				'holder'           => $order->getHolder(),
				'lastupdated'      => $order->getLastupdated(),
		        'description'       => $order->getDescription(),
				'valid'            => $order->getValid(),
		        'checked'          => $order->getChecked(),
		      'finalized'   => $order->getFinalized()
		);
		 
		return $this->update($data,array('orderno'=>$orderno));
		 
	}
	
	public function deleteOrderByShipmentId($shipment_id){
	    $subselect = new Select('shipment_order');
	    $subselect->columns(array(
	    	'orderno'
	    ))->where(array(
	    	'shipment_id' => $shipment_id
	    ));
	    
	    
	    $delete = new Delete($this->table);
	    $delete->where->addPredicate(
	       new In('orderno',$subselect)
	    );
	    
	    return $this->deleteWith($delete);
	    
	}
	
	
	public function deleteOrder($orderno){
	    return $this->delete(array('orderno' => $orderno));
	}
	
}