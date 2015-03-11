<?php

namespace Vhdang\Model;

class Customer {
	protected $_nick;
	protected $_password;
	protected $_balance_id;
	protected $_datecreated;
	protected $_lastupdated;
	protected $_active;
	protected $_service;
	protected $_shipping;
	protected $_group;
	
	
	protected $credit;
	
	
	public function getGroup(){
	    return $this->_group;
	}
	
	public function setGroup($_group){
	    $this->_group = $_group;
	}
	

	/**
	 * @return the $_service
	 */
	public function getService() {
		return $this->_service;
	}

	/**
	 * @return the $_shipping
	 */
	public function getShipping() {
		return $this->_shipping;
	}

	/**
	 * @param field_type $_service
	 */
	public function setService($_service) {
		$this->_service = $_service;
	}

	/**
	 * @param field_type $_shipping
	 */
	public function setShipping($_shipping) {
		$this->_shipping = $_shipping;
	}

	/**
	 * @return the $credit
	 */
	public function getCredit() {
		return $this->credit;
	}

	/**
	 * @param field_type $credit
	 */
	public function setCredit($credit) {
		$this->credit = $credit;
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
	 * @return the $_balance_id
	 */
	public function getBalance_id() {
		return $this->_balance_id;
	}

	/**
	 * @return the $_datecreated
	 */
	public function getDatecreated() {
		return $this->_datecreated;
	}

	/**
	 * @return the $_lastupdate
	 */
	public function getLastupdated() {
		return $this->_lastupdated;
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
	 * @param field_type $_balance_id
	 */
	public function setBalance_id($_balance_id) {
		$this->_balance_id = $_balance_id;
	}

	/**
	 * @param field_type $_datecreated
	 */
	public function setDatecreated($_datecreated) {
		$this->_datecreated = $_datecreated;
	}

	/**
	 * @param field_type $_lastupdate
	 */
	public function setLastupdated($_lastupdated) {
		$this->_lastupdated = $_lastupdated;
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