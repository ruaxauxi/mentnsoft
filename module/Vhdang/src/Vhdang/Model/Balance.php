<?php

namespace Vhdang\Model;

class Balance {
	
    protected $_id;
    protected $_credit;
    protected $_lastupdated;    
    
    
    
	
	/**
	 * @return the $_credit
	 */
	public function getCredit() {
		return $this->_credit;
	}

	/**
	 * @param field_type $_credit
	 */
	public function setCredit($_credit) {
		$this->_credit = $_credit;
	}

	/**
	 * @return the $_lastupdated
	 */
	public function getLastupdated() {
		return $this->_lastupdated;
	}

	/**
	 * @param field_type $_lastupdated
	 */
	public function setLastupdated($_lastupdated) {
		$this->_lastupdated = $_lastupdated;
	}

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