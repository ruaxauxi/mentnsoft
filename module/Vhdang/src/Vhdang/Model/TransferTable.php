<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

use \Vhdang\Model\Transfer;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Predicate\Predicate;

class TransferTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'transfer';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
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
	
	public function ExportTransfer($dateFrom, $dateTo){
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'id' => 'id',
	    		'nick' => 'nick',
	    		'x_rate'     => 'x_rate',
	    		'trans_date'     => 'trans_date',
	    		'datecreated'     => 'datecreated',
	    		'refno'     => 'refno',
	    		'trans_type'    => 'trans_type',
	    		'vnd'     => 'vnd',
	    		'usd'     => 'usd',
	    		'note'      => 'note',
	    		'status'    => 'status',
	              	       
	  
	    ),true)
	    ->where(array(
	    	new Operator('datecreated',Operator::OPERATOR_GREATER_THAN_OR_EQUAL_TO,$dateFrom),
	        new Operator('datecreated',Operator::OPERATOR_LESS_THAN_OR_EQUAL_TO,$dateTo)
	    ))
	   ;
	     
	    
	     
	    $resultSet = $this->selectWith($select);
	    $userlist = $resultSet->toArray();
	    $list = array();
	    foreach ($userlist as $row) {
	    	$transfer = new Transfer();
	    	$transfer->setData($row);
	    	$list[] = $transfer;
	    }
	    return $list;
	    
	}
	
	
	public function getTotalTransferByNick($nick,$status="waiting"){
	    
	    $select = new Select($this->table);
	    
	    $select->columns(array(
	    	'total' => new Expression('sum(usd)')
	    ))->where(array(
	    	'nick'    => $nick,
	        'status'   => $status
	    ));
	    

	    $resultSet = $this->selectWith($select);
	    $row = $resultSet->current();
	    return $row['total'];
	    
	}
	
	public function getConfirmTransfers($status, $nick=null){
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'id' => 'id',
	    		'nick' => 'nick',
	    		'x_rate'     => 'x_rate',
	    		'trans_date'     => 'trans_date',
	    		'datecreated'     => 'datecreated',
	    		'refno'     => 'refno',
	           'trans_type'    => 'trans_type',
	    		'vnd'     => 'vnd',
	    		'usd'     => 'usd',
	            'note'      => 'note',
	    		'status'    => 'status'
	    
	    ),true)
	    ->where(array('status'=>$status))
	    ->order('datecreated','DESC');
	    
	    if ($nick){
	        $select->where(array('nick'=> $nick));
	    }
	    
	    $resultSet = $this->selectWith($select);
	    $userlist = $resultSet->toArray();
	    $list = array();
	    foreach ($userlist as $row) {
	    	$transfer = new Transfer();
	    	$transfer->setData($row);
	    	$list[] = $transfer;
	    }
	    return $list;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'id' => 'id',
	    		'nick' => 'nick',
	    		'x_rate'     => 'x_rate',
	            'trans_date'     => 'trans_date',
	            'datecreated'     => 'datecreated',
	            'refno'     => 'refno',
	           'trans_type'    => 'trans_type',
	            'vnd'     => 'vnd',
	            'usd'     => 'usd',
	            'note'      => 'note',
	            'status'    => 'status'
    	    		 
	    ),true)
	      ->order('datecreated','DESC');
	     
	    $resultSet = $this->selectWith($select);
	    $userlist = $resultSet->toArray();
	    $list = array();
	    foreach ($userlist as $row) {	        
	        $transfer = new Transfer();
	        $transfer->setData($row);
	        $list[] = $transfer;
	    }
	    return $list;
	}
	public function checkTransfer($id,$status){
		$select = new Select($this->table);
		$select   -> columns(array(
				'id' => 'id',
				'nick' => 'nick',
				'x_rate'     => 'x_rate',
				'trans_date'     => 'trans_date',
				'datecreated'     => 'datecreated',
				'refno'     => 'refno',
		        'trans_type'    => 'trans_type',
				'vnd'     => 'vnd',
				'usd'     => 'usd',
		        'note'      => 'note',
		        'status'    => 'status'
	
		),true)
			
		->where(array('id' => $id,'status'=>$status));
	
		$resultSet = $this->selectWith($select);
		$row = (array)$resultSet->current();
		if ($row){
			$transfer = new Transfer();
			$transfer->setData($row);
			return $transfer;
		}else{
			return null;
		}
	
	
	}
	
	public function getTransferById($id){			
		$select = new Select($this->table);
		$select   -> columns(array(
				'id' => 'id',
				'nick' => 'nick',
				'x_rate'     => 'x_rate',
				'trans_date'     => 'trans_date',
				'datecreated'     => 'datecreated',
				'refno'     => 'refno',
		        'trans_type'    => 'trans_type',
				'vnd'     => 'vnd',
				'usd'     => 'usd',
		        'note'      => 'note',
				'status'    => 'status'
	
		),true)
		 
		->where(array('id' => $id));
	
		$resultSet = $this->selectWith($select);
		$row = (array)$resultSet->current();
		if ($row){
		    $transfer = new Transfer();
		    $transfer->setData($row);
		    return $transfer;
		}else{
		    return null;
		}
		
		
	}
	
	
	
	public function getTransferByNick($nick){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'id' => 'id',
				'nick' => 'nick',
				'x_rate'     => 'x_rate',
				'trans_date'     => 'trans_date',
				'datecreated'     => 'datecreated',
				'refno'     => 'refno',
		        'trans_type'    => 'trans_type',
				'vnd'     => 'vnd',
				'usd'     => 'usd',
		        'note'      => 'note',
				'status'    => 'status'
	
		),true)
		->order(array('datecreated'=>'DESC'))
		->where(array('nick' => $nick));
	
		$resultSet = $this->selectWith($select);
		$userlist = $resultSet->toArray();
		$list = array();
		foreach ($userlist as $row) {
			$transfer = new Transfer();
			$transfer->setData($row);
			$list[] = $transfer;
		}
		return $list;
	}
	
	
	public function getNicks($status){
			
		$select = new Select($this->table);
		$select   -> columns(array(				 
				'nick' => 'nick',
		),true)
		->group(array('nick'))
		->where(array('status' => $status));
	
		$resultSet = $this->selectWith($select);
		$list= $resultSet->toArray();		 
		return $list;
	}
	
	public function updateStatus($tid,$status){
	    $data = array(
	    	'status'  => $status
	    );
	    
	   return $this->update($data,array('id'=> $tid));
	    
	}
	
	public function saveTransfer(Transfer $transfer){
	    $data = array(	         
	        'nick' => $transfer->getNick(),
	        'x_rate'     => $transfer->getX_rate(),
	        'trans_date'     => $transfer->getTrans_date(),	         
	        'refno'     => $transfer->getRefno(),
	        'trans_type'    => $transfer->getTrans_type(),
	        'vnd'     => $transfer->getVnd(),
	        'usd'     => $transfer->getUsd(),
	        'note'      => $transfer->getNote(),
	        'status'    => $transfer->getStatus()
	    );
	    
	   /*  print_r($data);
	    die; */
	    
	    $this->insert($data);
	    return $this->getLastInsertValue();
	}
	
	public function deleteTransferByMonYear($month,$year,$status = 'received'){
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
	
	public function deleteTransfer($id,$status=null){
	    if ($status){
	        $this->delete(array('id' => $id,'status'=>$status));
	    }else{
	        $this->delete(array('id' => $id));
	    }
	   
	}
	
}