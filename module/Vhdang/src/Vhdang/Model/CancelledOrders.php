<?php

namespace Vhdang\Model;
use Vhdang\Utils\String;
 

class CancelledOrders {
    
    protected $_id;
    protected $_orderno;
    protected $_shipment_id;
     
    protected $_admin;
    protected $_items;
    protected $_total;
    protected $_total_web1;
    
    

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @return the $_total_web1
	 */
	public function getTotal_web1() {
		return $this->_total_web1;
	}

	/**
	 * @param field_type $_total_web1
	 */
	public function setTotal_web1($_total_web1) {
	    $_total_web1 = String::formatNumber($_total_web1);
		$this->_total_web1 = (double)$_total_web1;
	}

	/**
	 * @return the $_admin
	 */
	public function getAdmin() {
		return $this->_admin;
	}

	/**
	 * @param field_type $_admin
	 */
	public function setAdmin($_admin) {
		$this->_admin = $_admin;
	}

	/**
	 * @return the $_items
	 */
	public function getItems() {
		return $this->_items;
	}

	/**
	 * @param field_type $_items
	 */
	public function setItems($_items) {
	    $_items = String::formatNumber($_items);	    
		$this->_items = (int)$_items;
	}

	/**
	 * @return the $_shipment_id
	 */
	public function getShipment_id() {
		return $this->_shipment_id;
	}

	/**
	 * @param field_type $_shipment_id
	 */
	public function setShipment_id($_shipment_id) {
		$this->_shipment_id = $_shipment_id;
	}

	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

	 

	/**
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

	/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
	}

	 
	/**
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
	    $_total = String::formatNumber($_total);
		$this->_total = (double) $_total;
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