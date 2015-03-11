<?php

namespace Vhdang\Model;
use Vhdang\Utils\String;
 

class BackOrdered {
    
    protected $_orderno;
    protected $_shipment_id;
    
    protected $_items;
    protected $_date;

    
    
    

	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

	/**
	 * @return the $_shipment_id
	 */
	public function getShipment_id() {
		return $this->_shipment_id;
	}

	/**
	 * @return the $_items
	 */
	public function getItems() {
		return $this->_items;
	}

	/**
	 * @return the $_date
	 */
	public function getDate() {
		return $this->_date;
	}

	/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
	}

	/**
	 * @param field_type $_shipment_id
	 */
	public function setShipment_id($_shipment_id) {
		$this->_shipment_id = $_shipment_id;
	}

	/**
	 * @param field_type $_items
	 */
	public function setItems($_items) {
	    $_items = String::formatNumber($_items);	    
		$this->_items = (int) $_items;
	}

	/**
	 * @param field_type $_date
	 */
	public function setDate($_date) {
	    
	    $date = \DateTime::createFromFormat("d-m-Y", $_date);
	    if ($date){
	    	$this->_date =  $date->format('Y-m-d');
	    } else{
	    	$this->_date = $_date;
	    } 
	}

	function setData(Array $data = null) {
		if (is_array($data)){
			$methods = get_class_methods($this);
			foreach ($data as $method => $val){
				$method = 'set'. ucfirst($method);
				if (in_array($method,$methods)){
					$this->$method($val);
				}
			}
		}else{
			throw new \Exception("Cannot populate data, the paramaters must be an array");
		}
	}
    
}