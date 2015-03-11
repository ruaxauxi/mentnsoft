<?php

namespace Vhdang\Model;

class Image {
	
	 protected $_id;
	 protected $_name;
	 protected $_width;
	 protected $_height;
	 protected $_dir;
	 
	 
	/**
	 * @return the $_path
	 */
	public function getDir() {
		return $this->_dir;
	}

	/**
	 * @param field_type $_path
	 */
	public function setDir($_path) {
		$this->_dir = $_path;
	}

	/**
	 * @return the $_width
	 */
	public function getWidth() {
		return $this->_width;
	}

	/**
	 * @return the $_height
	 */
	public function getHeight() {
		return $this->_height;
	}

	/**
	 * @param field_type $_width
	 */
	public function setWidth($_width) {
		$this->_width = $_width;
	}

	/**
	 * @param field_type $_height
	 */
	public function setHeight($_height) {
		$this->_height = $_height;
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

	     
	/**
	 * Sets attributes for the class
	 * @param array $data
	 */
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
			throw new \Exception("Cannot populate data, the paramater must be an array");
		}
	}
}