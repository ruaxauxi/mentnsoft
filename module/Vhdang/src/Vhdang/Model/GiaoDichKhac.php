<?php

namespace Vhdang\Model;

class GiaoDichKhac{
   
   protected $_admin;
   protected $_nick;
   protected $_orderno;
   protected $_date;
   protected $_note;
   protected $_total;
   protected $_type;
   

	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
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
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
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
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

/**
	 * @return the $_type
	 */
	public function getType() {
		return $this->_type;
	}

/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

/**
	 * @param field_type $_date
	 */
	public function setDate($_date) {
		$this->_date = $_date;
	}

/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
	}

/**
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
		$this->_total = $_total;
	}

/**
	 * @param field_type $_type
	 */
	public function setType($_type) {
		$this->_type = $_type;
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