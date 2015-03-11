<?php

namespace Vhdang\Model;

use Vhdang\Utils\String;
class ShippingFee {
	
    protected $_id;
    protected $_shipment_id;
    protected $_admin;
    protected $_weight;
    protected $_total;
    protected $_note;
    protected $_date;
    
    
	/**
	 * @return the $_weight
	 */
	public function getWeight() {
		return $this->_weight;
	}

	/**
	 * @param field_type $_weight
	 */
	public function setWeight($_weight) {
	    $_weight = String::formatNumber($_weight);
		$this->_weight = $_weight;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_shipment_id
	 */
	public function getShipment_id() {
		return $this->_shipment_id;
	}

	/**
	 * @return the $_admin
	 */
	public function getAdmin() {
		return $this->_admin;
	}

	/**
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

	/**
	 * @return the $_note
	 */
	public function getNote() {
		return $this->_note;
	}

	/**
	 * @return the $_date
	 */
	public function getDate() {
		return $this->_date;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_shipment_id
	 */
	public function setShipment_id($_shipment_id) {
		$this->_shipment_id = $_shipment_id;
	}

	/**
	 * @param field_type $_admin
	 */
	public function setAdmin($_admin) {
		$this->_admin = $_admin;
	}

	/**
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
	    $_total = String::formatNumber($_total);
		$this->_total = $_total;
	}

	/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
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
			throw new \Exception("Cannot populate data, the parameters must be an array");
		}
	}
    
}