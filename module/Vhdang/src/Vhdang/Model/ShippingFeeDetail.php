<?php
namespace Vhdang\Model;

class ShippingFeeDetail{
    
     protected $_nick;
     protected $_price;
     protected $_weight;
     protected $_total;
     protected $_note;

	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @return the $_price
	 */
	public function getPrice() {
		return $this->_price;
	}

	/**
	 * @return the $_weight
	 */
	public function getWeight() {
		return $this->_weight;
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
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @param field_type $_price
	 */
	public function setPrice($_price) {
		$this->_price = $_price;
	}

	/**
	 * @param field_type $_weight
	 */
	public function setWeight($_weight) {
		$this->_weight = $_weight;
	}

	/**
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
		$this->_total = $_total;
	}

	/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
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