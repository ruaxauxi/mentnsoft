<?php

namespace Vhdang\Model;

class Store {
	
    protected $_id;
    protected $_name;
    protected $_active;
      
    protected $_total; // tong so cac order dang gom

	/**
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

	/**
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
		$this->_total = $_total;
	}

	/**
	 * @return the $_active
	 */
	public function getActive() {
		return $this->_active;
	}

	/**
	 * @param field_type $_active
	 */
	public function setActive($_active) {
		$this->_active = $_active;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_name
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_name
	 */
	public function setName($_name) {
		$this->_name = $_name;
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