<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\NotIn;


class CreditCardTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'creditcard';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function getCreditCardByHolder($holder){
	    $select = new Select($this->table);
	    $select   -> columns(array(	         
	    		'creditcard',
	    		'holder' => 'holder',
	    )
	    		,true)
	    		->order(array('holder'=> 'ASC'))
	    		->where(array('holder' => $holder))
	    		;
	    
	    $resultSet = $this->selectWith($select);
	    $list = $resultSet->toArray();
	    $items = array();
	    foreach ($list as $row) {
	    	$item = new CreditCard();
	    	$item->setData($row);
	    	$items[] = $item;
	    }
	    return $items;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'creditcard',
	    		'holder' => 'holder', 
	    )
	    		,true)
	    ->order(array('holder'=> 'ASC'))	     
	    ;
	     
	    $resultSet = $this->selectWith($select);
	    $list = $resultSet->toArray();
	    $items = array();
	    foreach ($list as $row) {	        
	        $item = new CreditCard();
	        $item->setData($row);
	        $items[] = $item;
	    }
	    return $items;
	}
	
	/**
	 * 
	 * @abstract lay tat ca cac creditcard tu bang creditcard va admin_order
	 * 
	 */
	public function getAllCreditCard(){
	    
	    $subselect = new Select('creditcard');
	    $subselect->columns(array(
	    	'creditcard' => 'creditcard'
	    ));
	    
	    $select1 = new Select('admin_order');
	    $select1->columns(array(
	    	new Expression('DISTINCT(admin_order.creditcard) as creditcard'),
	        'holder'   => 'holder'
	    ))->where(array(
	    	 new \Zend\Db\Sql\Predicate\NotIn('creditcard',$subselect)
	    ));
	    
	    $select2 = new Select($this->table);
	    
	    $select2->columns(array(
	    	'creditcard'  => 'creditcard',
	        'holder'       => 'holder'
	    ));
	    
	    $select2->combine($select1);
	    
	    $resultSet = $this->selectWith($select2);
	    $list = $resultSet->toArray();
	    $items = array();
	    
	    foreach ($list as $row) {
	    	$item = new CreditCard();
	    	$item->setData($row);
	    	$items[] = $item;
	    }
	    return $items;
	}
	
	public function getCreditCardById($id){
		
		$select = new Select($this->table);
		$select   -> columns(array(
	    		     'creditcard',
    	    		'holder' => 'holder',    	   
	          
	    ),true)
		->where(array(
			'creditcard' => $id
		));
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		if ($row){
		    $item = new CreditCard();
		    $item->setData($row);
		    
		    if($item->getCreditcard() != $id){
		        return  false;
		    }else{
		        return $item;
		    }
		    
		    
		}else{
		    return false;
		}
		
	}
	
	
	
	public function updateCreditCard(CreditCard $creditcard){
	    $data = array(	   	        
	        'holder' => $creditcard->getHolder()	           
	    );
	    	     
	    return $this->update($data,array('creditcard' => $creditcard->getCreditcard()));
	    
	}
	
	public function insertCreditCard(CreditCard $creditcard){
	    $data = array(
	    		'holder' => $creditcard->getHolder(),
	    		'creditcard'   => $creditcard->getCreditcard()
	    );
	    
	    return $this->insert($data);
	    
	}
	
	public function deleteCreditCard($id){
	    return $this->delete(array('creditcard' => $id));
	}
	
}