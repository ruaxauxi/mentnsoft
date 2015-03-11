<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

use \Vhdang\Model\Admin;

class AdminTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'admin';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'nick',
	    		'password' => 'password',
	    		'isroot'     => 'isroot',
	           'group'     => 'group'
    	    		 
	    )
	    		,true);
	    		// ->from(array('u' =>'user'))
	   // -> join(array('t' => 'usertype'),'t.id=user.usertype_id',array('usertype_name'    => 'discription'))
	  //  ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name'));
	    		//->where(array('user.id' => $id));
	     
	    $resultSet = $this->selectWith($select);
	    $userlist = $resultSet->toArray();
	    $list = array();
	    foreach ($userlist as $row) {	        
	        $item = new Admin();
	        $item->setData($row);
	        $list[] = $item;
	    }
	    return $list;
	}
	
	public function getUserLoginData($nick){
	  
	   
	   $select = new Select($this->table);
	   $select   -> columns(array(
            	   				'nick',
                	    		'password' => 'password',
                	    		'isroot'     => 'isroot',
	                           'group'     => 'group'
	   )
	               ,true)
            	  // ->from(array('u' =>'user'))
	   			//	-> join(array('t' => 'usertype'),'t.id=user.usertype_id',  array('usertype'    => 'discription')) 
	   				->where(array('nick' => $nick));
	   		 
	    $resultSet = $this->selectWith($select);
	                       
	    $row = $resultSet->current(); 
	    if ($row){
	        return (array) $row;
	    }else{
	       return array();
	    }
	}
	
	public function getUserById($nick){
		
		$select = new Select($this->table);
		$select   -> columns(array(
	    		    'nick',
    	    		'password' => 'password',
    	    		'isroot'     => 'isroot',
		           'group'     => 'group'
	          
	    ),true)
	    
	   /*  -> join(array('t' => 'usertype'),'t.id=user.usertype_id',
	    		         array('usertype_name'    => 'discription'))
	    ->join(array('c'=>'city'), 'c.id=user.city',array('city_name'=>'name')) */
	    ->where(array('nick' => $nick));
	     
	    $resultSet = $this->selectWith($select);
		$resultSet = $this->selectWith($select);
	
		$row = (array)$resultSet->current();
		$user = new Admin();
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
	
	public function addUser(Admin $user){
	    $data = array(
	            'nick'  => $user->getNick(),
	    		'password' => $user->getPassword(),
	    		'isroot'     => $user->getIsRoot(),
	           'group'     => $user->getGroup()
	    );
	    
	    $this->insert($data);
	    
	}
	
	public function saveUser(Admin $user){
	    $data = array(	    
	        'password' => $user->getPassword(),
	        'isroot'     => $user->getIsRoot(),
	        'group'     => $user->getGroup()
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