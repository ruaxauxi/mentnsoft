<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class AnswerTable extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'answer';
    public $adapter;
    
    function __construct(Adapter $adapter) {
    	$this->adapter = $adapter;
    }
    
    public function getAll(){
        $select = new Select($this->table);
       
        $select->columns(array(
        	'id'   => 'id',
            'question_id'   => 'question_id',
            'content'       => 'content',
            'nick'      => 'nick',
            'admin'     => 'admin',
            'datecreated'   => 'datecreated'
        ))->order(array('datecreated' => 'DESC'));
        
        $resultSet = $this->selectWith($select);
        $rows = $resultSet->toArray();
        $list = array();
        foreach($rows as $row){
            $item = new Answer();
            $item->setData($row);
            $list[] = $item;
        }
        
        return $list;
        
    }
    
    public function getAnswerByQuestionId($id){
        $select = new Select($this->table);
         
        $select->columns(array(
        		'id'   => 'id',
        		'question_id'   => 'question_id',
        		'content'       => 'content',
        		'nick'      => 'nick',
        		'admin'     => 'admin',
        		'datecreated'   => 'datecreated'
        ))->where(array(
        	   'question_id'   => $id
        ))->order(array('datecreated' => 'ASC'));
        
        
        $resultSet = $this->selectWith($select);
        $rows = $resultSet->toArray();
        $list = array();
        foreach($rows as $row){
        	$item = new Answer();
        	$item->setData($row);
        	$list[] = $item;
        }
        
        return $list;
    }
    
    public function getAnswerById($id){
        $select = new Select($this->table);
        $select->columns(array(
        		'id'   => 'id',
        		'question_id'   => 'question_id',
        		'content'       => 'content',
        		'nick'      => 'nick',
        		'admin'     => 'admin',
        		'datecreated'   => 'datecreated'
        ))->where(array('id' => $id));
        
        $resultSet = $this->selectWith($select);
        $row = $resultSet->current();
        
        if (!$row){
            return null;
        }
        
        $item = new Answer();
        $item->setData($row);
        
        return $item;
        
    }
    public function save(Answer $answer){
        
        $data = array(
        	'id'   => $answer->getId(),
            'question_id'   => $answer->getQuestion_id(),
            'content'       => $answer->getContent(),
            'nick'          => $answer->getNick(),
            'admin'         => $answer->getAdmin(),
           // 'datecreated'   => $answer->getDatecreated()
        );
        
        if ($answer->getId()){
            return $this->update($data,array('id'  => $answer->getId()));
        }else{
            return $this->insert($data);
        }
        
    }
    
    public function deleteAnswer($id){
        return $this->delete(array('id' => $id));
    }
    
}