<?php

namespace Vhdang\Model;
use Vhdang\Utils\String;

class BackorderedItems {
    protected $_id;
    protected $_order_detail_id;
    protected $_nick;
    protected $_items;
    protected $_total;
    protected $_note;
    protected $_shipment_id;
    

	 

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
	 * @return the $_note
	 */
	public function getNote() {
		return $this->_note;
	}

	/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
	}

	/**
	 * @return the $_order_detail_id
	 */
	public function getOrder_detail_id() {
		return $this->_order_detail_id;
	}

	/**
	 * @param field_type $_order_detail_id
	 */
	public function setOrder_detail_id($_order_detail_id) {
		$this->_order_detail_id = $_order_detail_id;
	}

	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @return the $_items
	 */
	public function getItems() {
		return $this->_items;
	}

	/**
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

	 

	/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @param field_type $_items
	 */
	public function setItems($_items) {
	    $_items = String::formatNumber($_items);
		$this->_items = $_items;
	}

	/**
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
	    $_total= String::formatNumber($_total);
		$this->_total = (double)$_total;
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