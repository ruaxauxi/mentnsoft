<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Delete;
 

class CustomerOrderTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'customer_order';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function getWaitingOrders($nick=null,$status = null,$order=null,$store_id=null){
			
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'   => 'id',
				'nick'   => 'nick',
				'store_id' => 'store_id',
				'datecreated' => 'datecreated',
				'approveddate'    => 'approveddate',
				'description' => 'description',
				'note'    => 'note',
				'status'       => 'status',
				'approvedby'   => 'approvedby'
	
		),true)
		->join(array('s'=>'store'), "s.id = $this->table.store_id",array('store_name'=>'name'));
		if (!$order){
			$select->order(array('datecreated'=> 'DESC'));
		}else{
			$select->order(array('datecreated'=> $order));
		}
	
			
			
		if ($nick){
			$select->where(array('nick'=>$nick));
		}
			
		if ($status){
			$select->where(array('status'=>$status));
		}
			
		if ($store_id){
			$select->where(array('store_id'=>$store_id));
		}
			
		$resultSet = $this->selectWith($select);
		$rows = $resultSet->toArray();
		$list = array();
		foreach ($rows as $row) {
			$order = new  CustomerOrder();
			$order->setData($row);
			$list[] = $order;
		}
		return $list;
	}
	public function customerListAll($nick=null,$status = null,$order=null,$store_id=null){
			
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'   => 'id',
				'nick'   => 'nick',
				'store_id' => 'store_id',
				'datecreated' => 'datecreated',
				'approveddate'    => 'approveddate',
				'description' => 'description',
				'note'    => 'note',
				'status'       => 'status',
				'approvedby'   => 'approvedby'
	
		),true)
		->join(array('s'=>'store'), "s.id = $this->table.store_id",array('store_name'=>'name'));
			
		if (!$order){
			$select->order(array('datecreated'=> 'DESC'));
		}else{
			$select->order(array('datecreated'=> $order));
		}
			
			
		if ($nick){
			$select->where(array('nick'=>$nick));
		}
			
		if ($status){
			$select->where(array('status'=>$status));
		}
			
		if ($store_id){
			$select->where(array('store_id'=>$store_id));
		}
			
		/* $resultSet = $this->selectWith($select);
		$rows = $resultSet->toArray();
		$list = array();
		foreach ($rows as $row) {
			$order = new  CustomerOrder();
			$order->setData($row);
			$list[] = $order;
		}
		return $list; */
		
		
		$resultSetPrototype = new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new CustomerOrder());
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
	
	
	public function fetchAllForPaging($nick=null,$status = null,$order=null,$store_id=null){
			
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'   => 'id',
				'nick'   => 'nick',
				'store_id' => 'store_id',
				'datecreated' => 'datecreated',
				'approveddate'    => 'approveddate',
				'description' => 'description',
				'note'    => 'note',
				'status'       => 'status',
				'approvedby'   => 'approvedby'
	
		),true)
		->join(array('s'=>'store'), "s.id = $this->table.store_id",array('store_name'=>'name'));
		if (!$order){
			$select->order(array('approveddate'=> 'DESC'));
		}else{
			$select->order(array('approveddate'=> $order));
		}
	
			
			
		if ($nick){
			$select->where(array('nick'=>$nick));
		}
			
		if ($status){
			$select->where(array('status'=>$status));
		}
			
		if ($store_id){
			$select->where(array('store_id'=>$store_id));
		}
		
		$resultSetPrototype = new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new CustomerOrder());
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
	
	public function getAll($nick=null,$status = null,$order=null,$store_id=null){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'   => 'id',
				'nick'   => 'nick',
				'store_id' => 'store_id',
				'datecreated' => 'datecreated',
		        'approveddate'    => 'approveddate',
				'description' => 'description',
				'note'    => 'note',
				'status'       => 'status',
				'approvedby'   => 'approvedby'
	
		),true)
		->join(array('s'=>'store'), "s.id = $this->table.store_id",array('store_name'=>'name'));
		if (!$order){
		    $select->order(array('approveddate'=> 'DESC'));
		}else{
		    $select->order(array('approveddate'=> $order));    
		}
		
		 
		 
		if ($nick){
			$select->where(array('nick'=>$nick));
		}
		 
		if ($status){
			$select->where(array('status'=>$status));
		}
		 
		if ($store_id){
			$select->where(array('store_id'=>$store_id));
		}
		 
		$resultSet = $this->selectWith($select);
		$rows = $resultSet->toArray();
		$list = array();
		foreach ($rows as $row) {
			$order = new  CustomerOrder();
			$order->setData($row);
			$list[] = $order;
		}
		return $list;
	}
	
	
	
	public function getCustomerRequests($nick=null,$status = null,$order=null,$store_id=null){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'id'   => 'id',
				'nick'   => 'nick',
				'store_id' => 'store_id',
				'datecreated' => 'datecreated',
				'approveddate'    => 'approveddate',
				'description' => 'description',
				'note'    => 'note',
				'status'       => 'status',
				'approvedby'   => 'approvedby'
	
		),true)
		->join(array('s'=>'store'), "s.id = $this->table.store_id",array('store_name'=>'name'));
		 
		if (!$order){
			$select->order(array('datecreated'=> 'DESC'));
		}else{
			$select->order(array('datecreated'=> $order));
		}
		 
		 
		if ($nick){
			$select->where(array('nick'=>$nick));
		}
		 
		if ($status){
			$select->where(array('status'=>$status));
		}
		 
		if ($store_id){
			$select->where(array('store_id'=>$store_id));
		}
		 
		$resultSet = $this->selectWith($select);
		$rows = $resultSet->toArray();
		$list = array();
		$balanceTable = new BalanceTable($this->adapter);
		$tranferTable = new TransferTable($this->adapter);
		foreach ($rows as $row) {
			$order = new  CustomerOrder();
			$order->setData($row);
			
			$balance = $balanceTable->getBalanceByNick($order->getNick());
			$waiting = $tranferTable->getTotalTransferByNick($order->getNick());
			
			$order->setBalance($balance->getCredit());
			$order->setWait_confirm($waiting);
			
			$list[] = $order;
		}
		
		
		return $list;
	}
	
	public function getMonthAndYear(){
		$select = new Select($this->table);
		$select->columns(array(
				'month' =>  new Expression('DISTINCT month(datecreated)'),
				'year' => new Expression('year(datecreated)')
		))
		->order(array(
				'year'    => 'DESC',
				'month'    => 'DESC'
		));
		 
		$resultSet = $this->selectWith($select);
		$rows = $resultSet->toArray();
		 
		 
		$list = array();
		foreach($rows as $r){
			$row = new DateClass();
			$row->setData($r);
			 
			$list[] = $row;
		}
		 
		 
		return $list;
	}
	
	public function fetchAll($nick=null,$status = null,$order=null,$store_id=null){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	            'id'   => 'id',
	    		'nick'   => 'nick',
	    		'store_id' => 'store_id',
	    		'datecreated' => 'datecreated',	    
	            'approveddate'    => 'approveddate',
	    		'description' => 'description',
		        'note'    => 'note',
	            'status'       => 'status',
	            'approvedby'   => 'approvedby'
	    		 
	    ),true)
	    ->join(array('s'=>'store'), "s.id = $this->table.store_id",array('store_name'=>'name'));
	    
	    if (!$order){
		    $select->order(array('approveddate'=> 'DESC'));
		}else{
		    $select->order(array('approveddate'=> $order));    
		}
	    
	    
	    if ($nick){
	        $select->where(array('nick'=>$nick));
	    }
	    
	    if ($status){
	    	$select->where(array('status'=>$status));
	    }
	    
	    if ($store_id){
	        $select->where(array('store_id'=>$store_id));
	    }
	    
	    $resultSet = $this->selectWith($select);
	    $rows = $resultSet->toArray();
	    $list = array();
	    foreach ($rows as $row) {	        
	        $order = new  CustomerOrder();
	        $order->setData($row);
	        $list[] = $order;
	    }
	    return $list;
	}
	
	public function getOrderById($id,$status = null){		 
		$select = new Select($this->table);
		$select   -> columns(array(
	    		'id'   => 'id',
	    		'nick'   => 'nick',
	    		'store_id' => 'store_id',
	    		'datecreated' => 'datecreated',	  
		        'approveddate'    => 'approveddate',
	    		'description' => 'description',
		        'note'    => 'note',
	            'status'       => 'status',
		        'approvedby'   => 'approvedby'
	          
	    ),true)
	    ->where(array('id' => $id));
		if ($status){
		    $select->where(array('status'=>$status));
		}
	     
	    $resultSet = $this->selectWith($select);
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		$order = new  CustomerOrder();
		$order->setData($row);
		
		return $order;
	}
	
	
	
	public function saveOrder(CustomerOrder $order){
	    $data = array(	    	            
	    		'nick'   => $order->getNick(),
	    		'store_id' => $order->getStore_id(),	    		    		 
	    		'description' => $order->getDescription(),
		        'note'    =>  $order->getNote(),
	            'approveddate'    => $order->getApproveddate(),
	            'status'       =>  $order->getStatus(),
	            'approvedby'   => $order->getApprovedby()
	    );
	    
	    $id = $order->getId();	   
	    if (!$id){
	        $this->insert($data);
	        return $this->getLastInsertValue();
	        
	    }else{
	        if ($this->getOrderById($id)){
	           return  $this->update($data,array('id' => $id));
	            
	        }else{
	        	throw new \Exception("The Order has id $id does not exist.");
	        }
	       
	    }        
	    
	}
	
	public function deleteTransferByMonYear($month,$year,$status = 'checked'){
		$delete = new Delete($this->table);
	
		$predicate1 = new Predicate();
		$predicate1->expression('month(datecreated) = ?',$month);
		$predicate2 = new Predicate();
		$predicate2->expression('year(datecreated) = ?',$year);
	
		$predicate2 = new Predicate();
		$predicate2->expression('status = ?',$status);
	
	
		$delete->where->addPredicate($predicate1,Predicate::COMBINED_BY_AND);
		$delete->where->addPredicate($predicate2,Predicate::COMBINED_BY_AND);
	
		return $this->deleteWith($delete);
	}
	
	
	public function deleteOrder($id){
	    $this->delete(array('id' => $id));
	}
	
}