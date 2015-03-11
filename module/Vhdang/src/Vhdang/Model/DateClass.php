<?php

namespace Vhdang\Model;

class DateClass {
	
    protected $_day;
    protected $_month;
    protected $_year;

    

	/**
	 * @return the $_day
	 */
	public function getDay() {
		return $this->_day;
	}

	/**
	 * @return the $_month
	 */
	public function getMonth() {
		return $this->_month;
	}

	/**
	 * @return the $_year
	 */
	public function getYear() {
		return $this->_year;
	}

	/**
	 * @param field_type $_day
	 */
	public function setDay($_day) {
		$this->_day = $_day;
	}

	/**
	 * @param field_type $_month
	 */
	public function setMonth($_month) {
		$this->_month = $_month;
	}

	/**
	 * @param field_type $_year
	 */
	public function setYear($_year) {
		$this->_year = $_year;
	}
	
	public function getMY(){
	    return $this->getMonth().'-'.$this->getYear();
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