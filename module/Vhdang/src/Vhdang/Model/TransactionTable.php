<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

use \Vhdang\Model\Transaction;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Predicate\IsNull;

class TransactionTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'transaction';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	
	public function ExportTransfer($dateFrom, $dateTo){
		 
		$select = new Select($this->table);
		 $select->columns(array(
	        'id' => 'id',
	        'admin' => 'admin',
	        'balance_id' => 'balance_id',
	        'trans_date' => 'trans_date',
	        'orderno'      => 'orderno',
	        'order_detail_id'=> 'order_detail_id',
	        'transfer_id'     => 'transfer_id',
	        'shipment_id' => 'shipment_id',
	        'date'        => 'date',
	        'type' => 'type',
	        'amount' => 'amount',
	        'credit'   => 'credit',
	        'note' => 'note',
	        'check'    => 'check'
	    ))
	    ->join(array('c' => 'customer'), "$this->table.balance_id = c.balance_id",array(
	    		'nick'   => 'nick'
	    ))
		->where(array(
				new Operator('trans_date',Operator::OPERATOR_GREATER_THAN_OR_EQUAL_TO,$dateFrom),
				new Operator('trans_date',Operator::OPERATOR_LESS_THAN_OR_EQUAL_TO,$dateTo)
		))
		->order(array(
			'id' => 'ASC'
		))
		;
	
		$resultSet = $this->selectWith($select);
		$userlist = $resultSet->toArray();
		$list = array();
		foreach ($userlist as $row) {
			$transfer = new Transaction();
			$transfer->setData($row);
			$list[] = $transfer;
		}
		return $list;
		 
	}
	
	
	public function getMonthAndYear(){
		$select = new Select($this->table);
		$select->columns(array(
				'month' =>  new Expression('DISTINCT month(trans_date)'),
				'year' => new Expression('year(trans_date)')
		))
		->where(array(
			new IsNull('orderno'),
		    new IsNull('order_detail_id'),
		    new IsNull('shipment_id'),
		    new IsNull('transfer_id'),		    
		))
		->order(array(
				'year'    => 'DESC',
				'month'    => 'DESC'
		));
		 
		
		$predicate3 = new Predicate();
		$predicate3->isNull('orderno');
		
		$predicate4 = new Predicate();
		$predicate4->isNull('order_detail_id');
		
		$predicate5 = new Predicate();
		$predicate5->isNull('shipment_id');
		
		$predicate6 = new Predicate();
		$predicate6->isNull('transfer_id');
		
		
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
	
	public function getAllByShipmentId($shipment_id){
	    
	    $orderNo = new Select('shipment_order');
	    $orderNo->columns(array(
	    	'orderno' => 'orderno'
	    ))->where(array(
	    	'shipment_id' => $shipment_id
	    ));
	    
	    $orderDetail = new Select('admin_order');
	    $orderDetail->columns(array(
	    	
	    ))->join(array('od' => 'order_details'), "admin_order.orderno = od.orderno",array(
	    	'order_detail_id' => 'id'
	    ))->join(array(
	    	'so' => 'shipment_order'
	    ),"so.orderno = admin_order.orderno" ,array())
	    ->where(array(
	    	'so.shipment_id' => $shipment_id
	    ));
	    
	    $select = new Select($this->table);
	    $select->columns(array(
	        'id' => 'id',
	        'admin' => 'admin',
	        'balance_id' => 'balance_id',
	        'trans_date' => 'trans_date',
	        'orderno'      => 'orderno',
	        'order_detail_id'=> 'order_detail_id',
	        'transfer_id'     => 'transfer_id',
	        'shipment_id' => 'shipment_id',
	        'date'        => 'date',
	        'type' => 'type',
	        'amount' => 'amount',
	        'credit'   => 'credit',
	        'note' => 'note',
	        'check'    => 'check'
	    ))
	    ->join(array('c' => 'customer'), "$this->table.balance_id = c.balance_id",array(
	    		'nick'   => 'nick'
	    ))
	    ->where(array(
	        new PredicateSet(array(
	        	new \Zend\Db\Sql\Predicate\In('orderno', $orderNo),
	            new \Zend\Db\Sql\Predicate\In('order_detail_id', $orderDetail),
	        	new Operator("$this->table.shipment_id",  Operator::OPERATOR_EQUAL_TO, $shipment_id),
	        ),PredicateSet::COMBINED_BY_OR)
	    ));
	    
	    $resultSet = $this->selectWith($select);
	    $transactions = $resultSet->toArray();
	    $list = array();
	    foreach ($transactions as $row) {
	    	$transaction = new Transaction();
	    	$transaction->setData($row);
	    	$list[] = $transaction;
	    }
	    return $list;
	    
	}
	
	public function fetchAll(){  	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    	'id' => 'id',
	        'admin' => 'admin',
	        'balance_id' => 'balance_id',
	        'trans_date' => 'trans_date',
	        'orderno'      => 'orderno',
	        'order_detail_id'=> 'order_detail_id',
	        'transfer_id'     => 'transfer_id',
	        'shipment_id' => 'shipment_id',
	        'date'        => 'date',
	        'type' => 'type',
	        'amount' => 'amount',
	        'credit'   => 'credit',
	        'note' => 'note',
	        'check'    => 'check'
    	    		 
	    ),true)
	      ->order('trans_date','DESC');
	     
	    $resultSet = $this->selectWith($select);
	    $transactions = $resultSet->toArray();
	    $list = array();
	    foreach ($transactions as $row) {	        
	        $transaction = new Transaction();
	        $transaction->setData($row);
	        $list[] = $transaction;
	    }
	    return $list;
	}
	
	/*
	 * lay tat ca cac trans nam trong dot union tat ca cac orders
	 *   */
	
	public function getTransactionsByShipmentId($shipment_id){
		$subselect = new Select('shipment_order');
		$subselect->columns(array(
				'orderno' => 'orderno'
		), true)
		->where(array(
				'shipment_id' => $shipment_id
		));
		 
		$select = new Select($this->table);
		$select->columns(array(
		    'id' => 'id',
		    'admin' => 'admin',
		    'balance_id' => 'balance_id',
		    'trans_date' => 'trans_date',
		    'orderno'  => 'orderno',
		    'order_detail_id'=> 'order_detail_id',
		    'transfer_id'     => 'transfer_id',
		    'shipment_id' => 'shipment_id',
		    'date'        => 'date',
		    'type' => 'type',
		    'amount' => 'amount',
		    'credit'   => 'credit',
		    'note' => 'note',
		))
		->join(array('c' => 'customer'), "$this->table.balance_id = c.balance_id",array(
			'nick'   => 'nick'
		))
		->where(array(
				'check'   => 1,				
				new PredicateSet(array(
		    		new \Zend\Db\Sql\Predicate\In('orderno', $subselect),
		    		new Operator("$this->table.shipment_id",  Operator::OPERATOR_EQUAL_TO, $shipment_id),
		    ),PredicateSet::COMBINED_BY_OR)
		));
			
		$resultSet = $this->selectWith($select);
		$result = $resultSet->toArray();
		$list = array();
		
		foreach($result as $row){
		    $item = new Transaction();
		    $item->setData($row);
		    
		    $list[] = $item;
		}
		
		return $list;
			
	}
	
	
	/**
	 * @abstract lay tong tien trong tat ca cac order nam trong dot
	 *
	 * @param unknown $shipment_id
	 */
	public function getTotalByOrderInShipment($shipment_id,$type="+"){
		$subselect = new Select('shipment_order');
		$subselect->columns(array(
				'orderno' => 'orderno'
		), true)
		->where(array(
				'shipment_id' => $shipment_id
		));
	    
	    $select = new Select($this->table);
		$select->columns(array(
				'total'   => new Expression('sum(amount)')
		))
		->where(array(
				'check'   => 1,
		        'type'    => $type,
		          new \Zend\Db\Sql\Predicate\In('orderno', $subselect)
		));
		 
		$resultSet = $this->selectWith($select);
		$result = $resultSet->current();
		 
		return $result['total'];
		 
	}
	
	
	/**
	 * @abstract lay tong tien trong tat ca cac dot, bao gom cac trans nam trong dot
	 * 
	 * @param unknown $shipment_id
	 */
	public function getTotalByShipment($shipment_id,$type="+"){
	    $select = new Select($this->table);
	    $select->columns(array(
	    	'total'   => new Expression('sum(amount)')
	    ))
	    ->where(array(
	    	'check'   => 1,
	        'type'    => $type,
	        'shipment_id'  => $shipment_id
	    ));
	    
	    $resultSet = $this->selectWith($select);
	    $result = $resultSet->current();
	    
	    return $result['total'];
	    
	}
	
	public function getAllAdminTransactionForPaging($admin=null,$nick=null,$date = null){
		$select = new Select($this->table);
		$select   -> columns(array(
				'id' => 'id',
				'admin' => 'admin',
				'balance_id' => 'balance_id',
				'trans_date' => 'trans_date',
		        'orderno'  => 'orderno',
				'order_detail_id'=> 'order_detail_id',
				'transfer_id'     => 'transfer_id',
				'shipment_id' => 'shipment_id',
				'date'        => 'date',
				'type' => 'type',
				'amount' => 'amount',
				'credit'   => 'credit',
				'note' => 'note',
		        'check'   => 'check'
	
		),true)
		->join(array('c'  => 'customer'), "$this->table.balance_id=c.balance_id",array('nick' => 'nick'))
		->where(array(
				'order_detail_id' => null,
				'transfer_id' => null
		))
		->order(array('trans_date' => 'DESC'));
	
		if ($admin){
			$select->where(array('admin'=> $admin));
		}
	
		if ($nick){
			$select->where(array('nick' => $nick));
		}
	
		if ($date){
			$select->where(array('date'   => $date));
		}
	
		/* $resultSet = $this->selectWith($select);
		$transactions = $resultSet->toArray();
		$list = array();
		foreach ($transactions as $row) {
			$transaction = new Transaction();
			$transaction->setData($row);
			$list[] = $transaction;
		} */
		
		
		
		$resultSetPrototype = new ResultSet();
		$resultSetPrototype->setArrayObjectPrototype(new Transaction());
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
	
	public function getAllAdminTransaction($admin=null,$nick=null,$date = null){
		$select = new Select($this->table);
		$select   -> columns(array(
				'id' => 'id',
				'admin' => 'admin',
				'balance_id' => 'balance_id',
				'trans_date' => 'trans_date',
		        'orderno'     => 'orderno',
				'order_detail_id'=> 'order_detail_id',
				'transfer_id'     => 'transfer_id',
		    'shipment_id' => 'shipment_id',
				'date'        => 'date',
				'type' => 'type',
				'amount' => 'amount',
				'credit'   => 'credit',
				'note' => 'note',
		        'check'   => 'check'
	
		),true)
		->join(array('c'  => 'customer'), "$this->table.balance_id=c.balance_id",array('nick' => 'nick'))
		->where(array(
		    'order_detail_id' => null,
		    'transfer_id' => null
		))		 
		->order(array('trans_date' => 'DESC'));
		
		if ($admin){
		    $select->where(array('admin'=> $admin));
		}
		
		if ($nick){
		    $select->where(array('nick' => $nick));
		}
		
		if ($date){
		    $select->where(array('date'   => $date));
		}
		
		$resultSet = $this->selectWith($select);
		$transactions = $resultSet->toArray();
		$list = array();
		foreach ($transactions as $row) {
			$transaction = new Transaction();
			$transaction->setData($row);
			$list[] = $transaction;
		}
		return $list;
	}
	
	public function getTransactionByBalanceId($balance_id,$limit=null,$offset = null){
		$select = new Select($this->table);
		$select   -> columns(array(
				'id' => 'id',
				'admin' => 'admin',
				'balance_id' => 'balance_id',
				'trans_date' => 'trans_date',
		        'orderno'     => 'orderno',
				'order_detail_id'=> 'order_detail_id',
				'transfer_id'     => 'transfer_id',
		    'shipment_id' => 'shipment_id',
				'date'        => 'date',
				'type' => 'type',
				'amount' => 'amount',
				'credit'   => 'credit',
				'note' => 'note',
		        'check'   => 'check'
	
		),true)
			
		->where(array('balance_id' => $balance_id))
		->order(array('trans_date' => 'DESC'));
		
		if ($limit){
			$select->limit($limit);
		}
		 
		if ($offset){
			$select->offset($offset);
		}
		 
		 $resultSet = $this->selectWith($select);
		 $transactions = $resultSet->toArray();
	    $list = array();
	    foreach ($transactions as $row) {	        
	        $transaction = new Transaction();
	        $transaction->setData($row);
	        $list[] = $transaction;
	    }
	    
	    return $list;
	 
	}
		
	public function getTransactionById($id){			
		$select = new Select($this->table);
		$select   -> columns(array(
			'id' => 'id',
	        'admin' => 'admin',
	        'balance_id' => 'balance_id',
	        'trans_date' => 'trans_date',
		    'orderno'     => 'orderno',
		    'order_detail_id'=> 'order_detail_id',
		    'transfer_id'     => 'transfer_id',
		    'shipment_id' => 'shipment_id',
		    'date'        => 'date',
	        'type' => 'type',
	        'amount' => 'amount',
		    'credit'   => 'credit',
	        'note' => 'note',
		    'check'   => 'check'
	
		),true)
		 
		->where(array('id' => $id));
	
		$resultSet = $this->selectWith($select);
		$row = (array)$resultSet->current();
		if ($row){
		    $transaction = new Transaction();
		    $transaction->setData($row);
		    return $transaction;
		}else{
		    return null;
		}
		
	}
	
	
	public function saveTransaction(Transaction $transaction){
	    $data = array(
	        'admin' => $transaction->getAdmin(),
	        'balance_id' => $transaction->getBalance_id(),	        
	        'type' => $transaction->getType(),
	        'date' => $transaction->getDate(),
	        'orderno'  => $transaction->getOrderno(),
	        'order_detail_id'=> $transaction->getOrder_detail_id(),
	        'transfer_id'  => $transaction->getTransfer_id(),
	        'shipment_id' =>  $transaction->getShipment_id(),
	        'amount' => $transaction->getAmount(),
	        'credit'   => $transaction->getCredit(),
	        'note' => $transaction->getNote(),
	        'check'    => $transaction->getCheck()
	    );
	    
	  
	    $this->insert($data);
	    return $this->getLastInsertValue();
	}
	
	
	public function deleteTransferByMonYear($month,$year){
		$delete = new Delete($this->table);
	
		$predicate1 = new Predicate();
		$predicate1->expression('month(trans_date) = ?',$month);
		$predicate2 = new Predicate();
		$predicate2->expression('year(trans_date) = ?',$year);
		
		$predicate3 = new Predicate();
		$predicate3->isNull('orderno');
		
		$predicate4 = new Predicate();
		$predicate4->isNull('order_detail_id');
		
		$predicate5 = new Predicate();
		$predicate5->isNull('shipment_id');
		
		$predicate6 = new Predicate();
		$predicate6->isNull('transfer_id');
		
	
		$delete->where->addPredicate($predicate1,Predicate::COMBINED_BY_AND);
		$delete->where->addPredicate($predicate2,Predicate::COMBINED_BY_AND);
		$delete->where->addPredicate($predicate3,Predicate::COMBINED_BY_AND);
		$delete->where->addPredicate($predicate4,Predicate::COMBINED_BY_AND);
		$delete->where->addPredicate($predicate5,Predicate::COMBINED_BY_AND);
		$delete->where->addPredicate($predicate6,Predicate::COMBINED_BY_AND);
	
		return $this->deleteWith($delete);
	}
	
	public function deleteTransaction($id){
	    $this->delete(array('id' => $id));
	}
	
}