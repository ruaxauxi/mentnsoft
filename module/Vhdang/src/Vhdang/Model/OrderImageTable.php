<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;


class OrderImageTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'order_image';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	
	public function getImageByOrderno($orderno){
		 
		$select = new Select($this->table);
		$select   -> columns(array(
				'orderno'	=> 'orderno',
				'image_id' => 'image_id',
				'path'     => 'path'
		)
				,true)
	   ->where(array('orderno' => $orderno))
				;
		 
		 
		$resultSet = $this->selectWith($select);
		$list = $resultSet->toArray();
		$items = array();
		foreach ($list as $row) {
			$item = new OrderImage();
			$item->setData($row);
			$items[] = $item;
		}
		return $items;
	}
	
	
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'orderno'	=> 'orderno',
	    		'image_id' => 'image_id', 
	            'path'     => 'path'
	    )
	    		,true)
	     
	    ;
	  
	    
	    $resultSet = $this->selectWith($select);
	    $list = $resultSet->toArray();
	    $items = array();
	    foreach ($list as $row) {	        
	        $item = new OrderImage();
	        $item->setData($row);
	        $items[] = $item;
	    }
	    return $items;
	}
	
	 
	
	public function saveOrderImage(OrderImage $image){
	    $data = array(	   	        
	        'orderno' => $image->getOrderno(),
	    	'image_id'	=> $image->getImage_id(),
	    	'path'		=> $image->getPath()	        
	    );
	    	     
	   return $this->insert($data);
	   
	    
	}
	
	public function deleteAllImage($orderno){
		$this->delete(array('orderno' => $orderno));
	}
	
	public function deleteImage($orderno,$image_id){
	    $this->delete(array('orderno' => $orderno,'image_id' => $image_id));
	}
	
}