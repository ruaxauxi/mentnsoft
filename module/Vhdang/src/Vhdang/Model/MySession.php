<?php

namespace Vhdang\Model;

use Zend\Session\Container;

class MySession extends Container {

    protected $_domain = "mentnonline";
    protected $_orderKey = "order";
    protected $_shippemntKey = "shipment";
    
    
    public function __construct(){
        parent::__construct($this->_domain);
    }
    
    // shipment
    
    public function saveShipmentInfo(array $data){
    	if ($this->offsetExists($this->_shippemntKey)){
    		$this->offsetUnset($this->_shippemntKey);
    	}
    	$this->offsetSet($this->_shippemntKey,$data);
    }
    
    public function getShipmentInfo(){
    	if ($this->offsetExists($this->_shippemntKey)){
    		return $this->offsetGet($this->_shippemntKey);
    	}else{
    		return array();
    	}
    }
    
    public function clearShipmentInfo(){
    	$this->offsetUnset($this->_shippemntKey);
    }
    
    // Order
    
    public function saveOrderInfo(array $data){
         if ($this->offsetExists($this->_orderKey)){
             $this->offsetUnset($this->_orderKey);
         }  
         $this->offsetSet($this->_orderKey,$data);
    }
    
    public function getOrderInfo(){
        if ($this->offsetExists($this->_orderKey)){
            return $this->offsetGet($this->_orderKey);
        }else{
            return array();
        }
    }
    
    public function clearOrderInfo(){
    	$this->offsetUnset($this->_orderKey);
    }
    
}