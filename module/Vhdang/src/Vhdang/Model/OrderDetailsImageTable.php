<?php

namespace Vhdang\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;


class OrderDetailsImageTable  extends AbstractTableGateway{
    
    public  $tableGateway;
    public $table = 'order_details_image';
    public $adapter;
    
	function __construct(Adapter $adapter) {
		$this->adapter = $adapter;
	}
	
	public function fetchAll(){  
	    
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'order_details_id'	=> 'order_details_id',
	    		'image_id' => 'image_id', 
	    		'path'		=> 'path'
	    )
	    		,true)
	     
	    ;
	  
	    
	    $resultSet = $this->selectWith($select);
	    $list = $resultSet->toArray();
	    $items = array();
	    foreach ($list as $row) {	        
	        $item = new OrderDetailsImage();
	        $item->setData($row);
	        $items[] = $item;
	    }
	    return $items;
	}
	
	public function getOrderDetailImages($detail_id){
	    $select = new Select($this->table);
	    $select   -> columns(array(
	    		'order_details_id'	=> 'order_details_id',
	    		'image_id' => 'image_id',
	    		'path'		=> 'path'
	           ),true)
	         ->where(array('order_details_id'    => $detail_id))
	    	;
	     
	     
	    $resultSet = $this->selectWith($select);
	    $list = $resultSet->toArray();
	    $items = array();
	    foreach ($list as $row) {
	    	$item = new OrderDetailsImage();
	    	$item->setData($row);
	    	$items[] = $item;
	    }
	    return $items;
	}
	
	
	public function saveOrderImage(OrderDetailsImage $image){
	    $data = array(	   	        
	        'order_details_id' => $image->getOrder_details_id(),
	    	'image_id'	=> $image->getImage_id(),
	    	'path'		=> $image->getPath()	        
	    );
	    	     
	   return $this->insert($data);
	   
	    
	}
	
	public function deleteAllImage($order_details_id){
		$this->delete(array('order_details_id' => order_details_id));
	}
	
	public function deleteImage($order_details_id,$image_id){
	    $this->delete(array('order_details_id' => $order_details_id,'image_id' => $image_id));
	}
	
}