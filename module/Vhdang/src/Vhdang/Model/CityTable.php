<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
 
class CityTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'city';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	/**
	 * @return array|null
	 */
	public function fetchAll(){  
	    $resultSet = $this->select(function (Select $select) {
	        $select->columns(array('id'=>'id','name'=> 'name'));
	    	  $select->order('id ASC');
	    });
	    
	    $cities = array();
	    $cities[0]   = '--chọn--';
	    foreach ($resultSet as $row) {
	    	$cities[$row->id] = $row->name;	     
	    }
	    return $cities;
	}
	
	
}