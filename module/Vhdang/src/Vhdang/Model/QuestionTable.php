<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Predicate\IsNotNull;

class QuestionTable extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'question';
    public $adapter;
    
    function __construct(Adapter $adapter) {
    	$this->adapter = $adapter;
    }
    
    
    public function getPM($nick = null){
    	$select = new Select($this->table);
    	 
    	$select->columns(array(
    			'id'   => 'id',
    			'nick'   => 'nick',
    			'admin' => 'admin',
    			'subject'   => 'subject',
    			'content'       => 'content',
    			'datecreated'   => 'datecreated',
    			'lastupdate'    => 'lastupdate',
    			'read'          => 'read',
    			'answer'        => 'answer'
    	))
    	->join(array('a' => 'answer'), "$this->table.id = a.question_id",array(
    			'count'    => new Expression('count(a.question_id)')
    	),SELECT::JOIN_LEFT)
    	->group(array("$this->table.id"))
    	->order(array('lastupdate' => 'DESC'));
    	 
    
    	if ($nick){
    		$select->where(array(
    		    "$this->table.nick"   =>  $nick,
    		     new IsNotNull("$this->table.admin")
    		));
    	}
    
     
    
    	$resultSetPrototype = new ResultSet();
    	$resultSetPrototype->setArrayObjectPrototype(new Question());
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
    
    public function getAll($nick = null,$type="all"){
        $select = new Select($this->table);
         
        $select->columns(array(
        	'id'   => 'id',
            'nick'   => 'nick',
            'admin' => 'admin',
            'subject'   => 'subject',
            'content'       => 'content',
            'datecreated'   => 'datecreated',
            'lastupdate'    => 'lastupdate',
            'read'          => 'read',
            'answer'        => 'answer'
        ))
        ->join(array('a' => 'answer'), "$this->table.id = a.question_id",array(
        	'count'    => new Expression('count(a.question_id)')
        ),SELECT::JOIN_LEFT)
        ->group(array("$this->table.id"))
        ->order(array('lastupdate' => 'DESC'));
       
        
        if ($nick){
            $select->where(array("$this->table.nick"=>$nick));
        }
        
        if ($type == 'unreply'){
            $select->where(array('answer' => 0));
        }elseif($type == 'reply'){
            $select->where(array('answer' => 1));
        }
        
        /* $resultSet = $this->selectWith($select);
        $rows = $resultSet->toArray();
        $list = array();
        foreach($rows as $row){
            $item = new Question();
            $item->setData($row);
            $list[] = $item;
        }
        
        return $list; */
        
        
        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new Question());
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
    
    public function getTotalUnread($nick){
    	$select = new Select($this->table);
    
    	$select->columns(array(
    			'total' => new Expression('count(*)')
    	))
    	->where(array(
    			'read' => 0,
    	       new IsNotNull('admin'),
    	       'nick' => $nick
    	));
    
    	$resultSet = $this->selectWith($select);
    	$row = $resultSet->current();
    
    	return $row['total'];
    }
    
    
    public function getTotalUnreply(){
        $select = new Select($this->table);
        
        $select->columns(array(
        	'total' => new Expression('count(*)')
        ))
        ->where(array(
        	'answer' => 0
        ));
        
        $resultSet = $this->selectWith($select);
        $row = $resultSet->current();
        
        return $row['total'];
    }
    
    
    public function getTotalReply(){
    	$select = new Select($this->table);
    
    	$select->columns(array(
    			'total' => new Expression('count(*)')
    	))
    	->where(array(
    			'answer' => 1
    	));
    
    	$resultSet = $this->selectWith($select);
    	$row = $resultSet->current();
    
    	return $row['total'];
    }
    
    public function getTotalQuestion(){
    	$select = new Select($this->table);
    
    	$select->columns(array(
    			'total' => new Expression('count(*)')
    	))
    	 ;
    
    	$resultSet = $this->selectWith($select);
    	$row = $resultSet->current();
    
    	return $row['total'];
    }
    
    
    public function getQuestionById($id,$nick=null){
        $select = new Select($this->table);
        $select->columns(array(
        		'id'   => 'id',
                'nick'   => 'nick',
                'admin' => 'admin',
                'subject'   => 'subject',
                'content'       => 'content',
                'datecreated'   => 'datecreated',
                'lastupdate'    => 'lastupdate',
                'read'          => 'read',
                'answer'        => 'answer'
        ))->where(array('id' => $id));
        
        if ($nick){
            $select->where(array('nick'=>$nick));
        }
        
        $resultSet = $this->selectWith($select);
        $row = $resultSet->current();
        
        if (!$row){
            return null;
        }
        $row = (array)$row;
        $item = new Question();
        $item->setData($row);
        
        return $item;
        
    }
    
    public function updateRead(Question $q){
        
      $data = array(
        		'id'   => $q->getId(),
        		'nick'   => $q->getNick(),
        		'subject'   => $q->getSubject(),
        		'content'       => $q->getContent(),
        		'datecreated'   => $q->getDatecreated(),
        		'lastupdate'    => $q->getLastupdate(),
        		'read'          => $q->getRead(),
        		'answer'        => $q->getAnswer()
        );
      
      return $this->update($data,array('id'  => $q->getId()));
    }
    
    public function save(Question $q){
        
        $data = array(
        		'id'   => $q->getId(),
        		'nick'   => $q->getNick(),
                'admin' => $q->getAdmin(),
        		'subject'   => $q->getSubject(),
        		'content'       => $q->getContent(),
        		'datecreated'   => $q->getDatecreated(),
        		//'lastupdate'    => $q->getLastupdate(),
        		'read'          => $q->getRead(),
        		'answer'        => $q->getAnswer()
        );
       
        
        if ($q->getId()){
            return $this->update($data,array('id'  => $q->getId()));
        }else{
            return $this->insert($data);
        }
        
    }
    
    public function deleteQuestion($id){
        return $this->delete(array('id' => $id));
    }
    
}