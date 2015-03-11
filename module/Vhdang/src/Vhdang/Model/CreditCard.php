<?php

namespace Vhdang\Model;

class CreditCard {
	
     
    protected $_holder;

    protected $_creditcard;

	/**
	 * @return the $_creditcard
	 */
	public function getCreditcard() {
		return $this->_creditcard;
	}

	/**
	 * @param field_type $_creditcard
	 */
	public function setCreditcard($_creditcard) {
		$this->_creditcard = $_creditcard;
	}

	 
	/**
	 * @return the $_holder
	 */
	public function getHolder() {
		return $this->_holder;
	}

	 

	/**
	 * @param field_type $_holder
	 */
	public function setHolder($_holder) {
		$this->_holder = $_holder;
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