<?php

namespace Vhdang\Model;

class Admin {
	protected $_nick;
	protected $_password;
	protected $_isRoot;
	protected $_active;
	protected $_group;
	
	
	public function getGroup(){
		return $this->_group;
	}
	
	public function setGroup($_group){
		$this->_group = $_group;
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
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @return the $_password
	 */
	public function getPassword() {
		return $this->_password;
	}

	/**
	 * @return the $_isRoot
	 */
	public function getIsRoot() {
		return (int)$this->_isRoot;
	}

	/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @param field_type $_password
	 */
	public function setPassword($_password) {
		$this->_password = $_password;
	}

	/**
	 * @param field_type $_isRoot
	 */
	public function setIsRoot($_isRoot) {
		$this->_isRoot = $_isRoot;
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