<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

use \Vhdang\Model\Address;
use \Vhdang\Model\CustomerTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Operator;
use Zend\Db\Sql\Predicate\IsNull;
use Zend\Db\Sql\Predicate\In;
use Zend\Db\Sql\Predicate\Predicate;
use Zend\Db\Sql\Predicate\Like;

class AddressTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'address';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'id' => 'id',
	    		'nick' => 'nick',
	           'receiver'  => 'receiver',
	           'shipping_method_id',
	    		'address'     => 'address',
	           'email'     => 'email',
	            'city'     => 'city',
	           'telephone'     => 'telephone',
	           'datecreated'     => 'datecreated',
	           'active'     => 'active',
    	    		 
	    ),true)
	    	->join(array('sm' => 'shipping_method'), "sm.id = $this->table.shipping_method_id",array(
	    		'shipping_method'    => 'shipping_method'
	    	))	 
	    	->where(array('active' => 1));
	     
	    $resultSet = $this->selectWith($select);
	    $userlist = $resultSet->toArray();
	    $list = array();
	    foreach ($userlist as $row) {	        
	        $address = new Address();
	        $address->setData($row);
	        $list[] = $address;
	    }
	    return $list;
	}
	
	public function getCustomersAddress($shipment_id="",$method="",$search_nick=null){
	    
	    /* 
	    SELECT c.nick, a.id,a.receiver,a.shipping_method_id,a.address,a.city,a.email,a.telephone FROM customer c LEFT JOIN address a
	    ON c.nick = a.nick
	    WHERE (a.id = (SELECT max(id) From address a1 WHERE a1.nick = c.nick) OR a.id is null)
	    GROUP BY c.nick
	    ORDER BY c.nick
	    LIMIT 0,500
	     */
	    
	    $sql = new Sql($this->adapter);
	    
	    $subQry = $sql->select()
	    ->from(array('a1' => 'address'))
	    ->columns(array('id' => new \Zend\Db\Sql\Expression('max(a1.id)')))
	    ->where('a1.nick = a.nick');
	     
	    
	    $mainSelect = $sql->select()->from(array('c' =>'customer'));
	    $mainSelect->columns(
	    		array(
	    				'nick' => 'nick',
	    		)
	    )->join(array('a' => 'address'), "c.nick = a.nick",array(
	    	'id' => 'id',
	        'receiver' => 'receiver',
	        'shipping_method_id' => 'shipping_method_id',
	        'address' => 'address',
	        'email'    => 'email',
	        'telephone'    => 'telephone',
	        'datecreated'  => 'datecreated'
	    ),Select::JOIN_LEFT)
	    ->join(array('city' => 'city'), "city.id = a.city",array(
	    	'city_name' => 'name'
	    ),Select::JOIN_LEFT)
	    ->join(array('sm' => 'shipping_method'), "sm.id = a.shipping_method_id",array(
	    	'shipping_method' => 'shipping_method'
	    ),Select::JOIN_LEFT)
	    ->where(array(
	    	new PredicateSet(array(
	    	    new In('a.id',$subQry),
	    	    new IsNull('a.id')
	    	),Predicate::COMBINED_BY_OR)
	    ))
	    ->group(array('c.nick'))	    
	    ;
	    
	    if ($method){
	        $mainSelect->where(array(
	        	'shipping_method_id' => $method
	        ));
	    }
	    
	    if ($shipment_id){
	        $sub = new Select('shipment_order');
	        $sub->columns(array())
	        ->join(array('o' => 'admin_order'),"o.orderno = shipment_order.orderno",array())
	        ->join(array('od' => 'order_details'),"od.orderno = o.orderno",array(
	        	'nick'    => 'nick'
	        ))
	        ->where(array(
	        	'shipment_order.shipment_id' => $shipment_id
	        ));
	        
	        $mainSelect->where(array(
	        	   new In('c.nick', $sub)
	        ));
	        
	    }
	    
	    if ($search_nick){
	        $search_nick = '%'.$search_nick.'%';
	        $mainSelect->where(array(
	        		new Like('c.nick',$search_nick)
	        ));
	    }
	    
	    
	    $statement = $sql->prepareStatementForSqlObject($mainSelect);
	   
	    $comments = $statement->execute();
	    $resultSet = new ResultSet();
	    $resultSet->initialize($comments);
	    
	    $rows =  $resultSet->toArray();
	    
	    $list = array();
	    foreach ($rows as $row) {
	    	$address = new Address();
	    	$address->setData($row);
	    	$list[] = $address;
	    }
	    return $list;
	    
	}
	
	public function getAddressById($id){
		$id = (int)$id;
		$select = new Select($this->table);
		$select   -> columns(array(
	    		     'id' => 'id',
    	    		'nick' => 'nick',
		          'receiver'  => 'receiver',
		             'shipping_method_id',
    	    		'address'     => 'address',
		             'email'     => 'email',
    	            'city'     => 'city',
    	           'telephone'     => 'telephone',
    	           'datecreated'     => 'datecreated',
    	           'active'     => 'active',
	          
	    ),true)	
	    ->join(array('c' => 'city'), "c.id=$this->table.city",array(
	    	'city_name' => 'name'
	    ))    
	    ->join(array('sm' => 'shipping_method'), "sm.id = $this->table.shipping_method_id",array(
	    		'shipping_method'    => 'shipping_method'
	    ))
	    ->where(array('id' => $id));
	     
	    $resultSet = $this->selectWith($select);
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		  $address = new Address();
	      $address->setData($row);
		
		return $address;
	}
	
	public function getCurrentAddress($nick){
	 
		$select = new Select($this->table);
		$select   -> columns(array(
				'id' => 'id',
				'nick' => 'nick',
		          'receiver'  => 'receiver',
		        'shipping_method_id',
				'address'     => 'address',
				'email'     => 'email',
				'city'     => 'city',
				'telephone'     => 'telephone',
				'datecreated'     => 'datecreated',
				'active'     => 'active',
				 
		),true)
		->join(array('c' => 'city'), "c.id=$this->table.city",array(
				'city_name' => 'name'
		))
		->join(array('sm' => 'shipping_method'), "sm.id = $this->table.shipping_method_id",array(
				'shipping_method'    => 'shipping_method'
		))
		->order(array('datecreated'=>'DESC')) 
		->limit(1)
		->where(array('nick' => $nick));
	
		$resultSet = $this->selectWith($select);
		 
		$row = (array)$resultSet->current();
		$address = new Address();
		$address->setData($row);
	
		return $address;
	}
	
	 
	public function addAddress(Address $address){
	    $data = array(	           
    	    	'nick' => $address->getNick(),
	           'receiver'  =>  $address->getReceiver(),
	            'shipping_method_id' => $address->getShipping_method_id(),
    	    	'address'     => $address->getAddress(),
	            'email'       => $address->getEmail(),
    	        'city'     => $address->getCity(),
    	        'telephone'     => $address->getTelephone(),
    	        'datecreated'     => $address->getDatecreated(),
    	        
	    );
	    
	    $this->insert($data);	    
	    return $this->getLastInsertValue();
	    
	}
	
	
	 
	
	public function deleteAddress($id){
	    $this->delete(array('id' => $id));
	}
	
}