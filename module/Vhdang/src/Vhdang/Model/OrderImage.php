<?php

namespace Vhdang\Model;
use Vhdang\Utils\String;

class OrderImage {
   
	protected $_orderno;
	protected $_image_id;
	protected $_path;
	

	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

	/**
	 * @return the $_image_id
	 */
	public function getImage_id() {
		return $this->_image_id;
	}

	/**
	 * @return the $_path
	 */
	public function getPath() {
		return $this->_path;
	}

	/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
	}

	/**
	 * @param field_type $_image_id
	 */
	public function setImage_id($_image_id) {
		$this->_image_id = $_image_id;
	}

	/**
	 * @param field_type $_path
	 */
	public function setPath($_path) {
		$this->_path = $_path;
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