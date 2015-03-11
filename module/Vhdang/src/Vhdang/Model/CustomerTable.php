<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
 

class CustomerTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'customer';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'nick',
	    		'password' => 'password',
	    		'balance_id' => 'balance_id',
	    		'datecreated' => 'datecreated',
	    		'lastupdated' => 'lastupdated',
	            'service'     => 'service',
	            'shipping'    => 'shipping',
	            'group'       => 'group'
	    		 
	    )
	    		,true)
	    		// ->from(array('u' =>'user'))
	   // -> join(array('t' => 'usertype'),'t.id=user.usertype_id',array('usertype_name'    => 'discription'))
	       -> join(array('b' => 'balance'),"b.id=$this->table.balance_id",  array('credit'    => 'credit'))
	    		//->where(array('user.id' => $id));
	     ;
	    $resultSet = $this->selectWith($select);
	    $userlist = $resultSet->toArray();
	    $users = array();
	    foreach ($userlist as $row) {	        
	        $user = new Customer();
	        $user->setData($row);
	        $users[] = $user;
	    }
	    return $users;
	}
	
	public function getUserLoginData($nick){
	  
	   
	   $select = new Select($this->table);
	   $select   -> columns(array(
            	   				'nick',
                	    		'password' => 'password',
                	    		'balance_id' => 'balance_id',
                	    		'datecreated' => 'datecreated',
                	    		'lastupdated' => 'lastupdated',
                    	        'service'     => 'service',
                    	        'shipping'    => 'shipping',
	                           'group'       => 'group'
	                            
	               )
	               ,true)
            	  // ->from(array('u' =>'user'))
	   			-> join(array('b' => 'balance'),"b.id=$this->table.balance_id",  array('credit'    => 'credit')) 
	   				->where(array('nick' => $nick));
	   		 
	    $resultSet = $this->selectWith($select);
	                       
	    $row = $resultSet->current(); 
	    if ($row){
	        return (array) $row;
	    }else{
	       return array();
	    }
	}
	
	public function getUserByNick($nick){
			
		$select = new Select($this->table);
		$select   -> columns(array(
				'nick',
				'password' => 'password',
				'balance_id' => 'balance_id',
				'datecreated' => 'datecreated',
				'lastupdated' => 'lastupdated',
				'service'     => 'service',
				'shipping'    => 'shipping',
		        'group'       => 'group'
	     
		),true)
		 
		-> join(array('b' => 'balance'),"b.id=$this->table.balance_id",  array('credit'    => 'credit'))
		->where(array('nick' => $nick));
	
		$resultSet = $this->selectWith($select);
	 
		$row = (array)$resultSet->current();
		
		if ($row){
		    $user = new Customer();
		    $user->setData($row);
		}else{
		   $user = array(); 
		}
		
	
		return $user;
	}
 	
	public function getUserById($nick){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
	    		     'nick',
    	    		'password' => 'password',
    	    		'balance_id' => 'balance_id',
    	    		'datecreated' => 'datecreated',
    	    		'lastupdated' => 'lastupdated',
        		    'service'     => 'service',
        		    'shipping'    => 'shipping',
		            'group'       => 'group'
	          
	    ),true)
	    
	    -> join(array('b' => 'balance'),"b.id=$this->table.balance_id",  array('credit'    => 'credit'))
	    ->where(array('nick' => $nick));
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		$user = new Customer();
		$user->setData($row);
		
		return $user;
	}
	
	public function isValidNick($nick){
	 
	    $row = $this->select(array('nick' => $nick))->current();
	    if (!$row){
	    	return true;
	    }else{
	    	return false;
	    }
	}
	
	public function checkPassword($nick,$pass){
	
		$row = $this->select(array('nick' => $nick, 'password' => $pass))->current();
		if ($row){
			return true;
		}else{
			return false;
		}
	}
	
	public function addUser(Customer $user){
	    $data = array(
	           'nick'  => $user->getNick(),
	    		'password' => $user->getPassword(),
	    		'balance_id' => $user->getBalance_id(),
	    		'datecreated' => $user->getDatecreated(),
	    		'lastupdated' => $user->getLastupdated(),
	            'service'     => $user->getService(),
	            'shipping'    => $user->getShipping()
	    );
	    
	    $this->insert($data);
	    
	}
	
	public function saveUser(Customer $user){
	    $data = array(	    
	        'password' => $user->getPassword(),
	        'balance_id' => $user->getBalance_id(),
	        'datecreated' => $user->getDatecreated(),
	        'lastupdated' => $user->getLastupdated(),
	        'service'     => $user->getService(),
	        'shipping'    => $user->getShipping(),	        
	    );
	    
	    $nick = $user->getNick();	   
	    
        if ($this->getUserById($nick)){
           return $this->update($data,array('nick' => $nick));
        }else{
            throw new \Exception("The User has nick $nick does not exist.");
        }
	     
	}
	
	public function deleteUser($nick){
	    $this->delete(array('nick' => $nick));
	}
	
}