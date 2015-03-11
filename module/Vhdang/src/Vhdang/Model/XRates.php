<?php

namespace Vhdang\Model;

class XRates {
	
    protected $_id;
    protected $_rate;
    protected $_date;

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_rate
	 */
	public function getRate() {
		return $this->_rate;
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
	 * @param field_type $_rate
	 */
	public function setRate($_rate) {
		$this->_rate = $_rate;
	}

	/**
	 * @param field_type $_date
	 */
	public function setDate($_date) {
		$this->_date = $_date;
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