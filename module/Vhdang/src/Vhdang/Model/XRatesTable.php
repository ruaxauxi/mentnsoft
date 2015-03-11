<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

use \Vhdang\Model\XRates;

class XRatesTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'x_rates';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'id' => 'id',
	    		'rate' => 'rate',
	    		'date'     => 'date',	 
	    ),true)
	    ->order(array('date'=>'DESC'))
	    		 
	    ;
	     
	    $resultSet = $this->selectWith($select);
	    $userlist = $resultSet->toArray();
	    $list = array();
	    foreach ($userlist as $row) {	        
	        $rate = new XRates();
	        $rate->setData($row);
	        $list[] = $rate;
	    }
	    return $list;
	}
	
	
	
	public function getCurrentRate(){		 
		$select = new Select($this->table);
		$select   -> columns(array(
	    		   'id' => 'id',
	    		    'rate' => 'rate',
	    		     'date'     => 'date',	
	          
	    ),true)	    	  
	    ->order(array('date'=>'DESC'))
	    ->limit(1);
	     
	    $resultSet = $this->selectWith($select);
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		    $rate = new XRates();
	        $rate->setData($row);
	        		
		return $rate;
	}
	
	
	public function addRate(XRates $rate){
	    $data = array(	           
    	    	'rate' => $rate->getRate(),
	    );
	    
	    $this->insert($data);	    
	    return $this->getLastInsertValue();
	    
	}
	
	 
	public function deleteX_rate($id){
	    $this->delete(array('id' => $id));
	}
	
}