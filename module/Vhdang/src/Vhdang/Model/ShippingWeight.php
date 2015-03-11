<?php

namespace Vhdang\Model;

use Vhdang\Utils\String;
class ShippingWeight {
	
   
    protected $_shipment_id;
    protected $_weight;
    protected $_price;
    protected $_total;
    protected $_date;
    protected $_note;
    
    
    protected $_nick;
    protected $_total_item;
    
	 
 
	/**
	 * @return the $_price
	 */
	public function getPrice() {
		return $this->_price;
	}

	/**
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

	/**
	 * @param field_type $_price
	 */
	public function setPrice($_price) {
		$this->_price = $_price;
	}

	/**
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
		$this->_total = $_total;
	}

	/**
	 * @return the $_total_item
	 */
	public function getTotal_item() {
		return $this->_total_item;
	}

	/**
	 * @param field_type $_total_item
	 */
	public function setTotal_item($_total_item) {
		$this->_total_item = $_total_item;
	}

	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	 
	/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	 

	/**
	 * @return the $_shipment_id
	 */
	public function getShipment_id() {
		return $this->_shipment_id;
	}

	/**
	 * @return the $_weight
	 */
	public function getWeight() {
		return $this->_weight;
	}

	/**
	 * @return the $_date
	 */
	public function getDate() {
		return $this->_date;
	}

	/**
	 * @return the $_note
	 */
	public function getNote() {
		return $this->_note;
	}

	 
	/**
	 * @param field_type $_shipment_id
	 */
	public function setShipment_id($_shipment_id) {
		$this->_shipment_id = $_shipment_id;
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

	/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
	}

	 
	public function setWeight($_weight) {
		$_weight = String::formatNumber($_weight);
		$this->_weight = $_weight;
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