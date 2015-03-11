<?php

namespace Vhdang\Model;

class ShippingMethod {
	
     protected $_id;
     protected $_shipping_method;

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_shipping_method
	 */
	public function getShipping_method() {
		return $this->_shipping_method;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_shipping_method
	 */
	public function setShipping_method($_shipping_method) {
		$this->_shipping_method = $_shipping_method;
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