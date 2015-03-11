<?php

namespace Vhdang\Model;

class Address {
	
    protected $_id;
    protected $_nick;
    protected $_receiver;
    protected $_shipping_method_id;
    protected $_address;
    protected $_email;
    protected $_city;
    protected $_telephone;
    protected $_datecreated;
    protected $_active;
    
    protected $_city_name;
    protected $_shipping_method;
   
    

	/**
	 * @return the $_city_name
	 */
	public function getCity_name() {
		return $this->_city_name;
	}

	/**
	 * @param field_type $_city_name
	 */
	public function setCity_name($_city_name) {
		$this->_city_name = $_city_name;
	}

	/**
	 * @return the $_receiver
	 */
	public function getReceiver() {
		return $this->_receiver;
	}

	/**
	 * @param field_type $_receiver
	 */
	public function setReceiver($_receiver) {
		$this->_receiver = $_receiver;
	}

	/**
	 * @return the $_shipping_method
	 */
	public function getShipping_method() {
		return $this->_shipping_method;
	}

	/**
	 * @param field_type $_shipping_method
	 */
	public function setShipping_method($_shipping_method) {
		$this->_shipping_method = $_shipping_method;
	}

	/**
	 * @return the $_shipping_method_id
	 */
	public function getShipping_method_id() {
		return $this->_shipping_method_id;
	}

	/**
	 * @param field_type $_shipping_method_id
	 */
	public function setShipping_method_id($_shipping_method_id) {
		$this->_shipping_method_id = $_shipping_method_id;
	}

	/**
	 * @return the $_email
	 */
	public function getEmail() {
		return $this->_email;
	}

	/**
	 * @param field_type $_email
	 */
	public function setEmail($_email) {
		$this->_email = $_email;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @return the $_address
	 */
	public function getAddress() {
		return $this->_address;
	}

	/**
	 * @return the $_city
	 */
	public function getCity() {
		return $this->_city;
	}

	/**
	 * @return the $_telephone
	 */
	public function getTelephone() {
		return $this->_telephone;
	}

	/**
	 * @return the $_datecreated
	 */
	public function getDatecreated() {
		return $this->_datecreated;
	}

	/**
	 * @return the $_active
	 */
	public function getActive() {
		return $this->_active;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @param field_type $_address
	 */
	public function setAddress($_address) {
		$this->_address = $_address;
	}

	/**
	 * @param field_type $_city
	 */
	public function setCity($_city) {
		$this->_city = $_city;
	}

	/**
	 * @param field_type $_telephone
	 */
	public function setTelephone($_telephone) {
		$this->_telephone = $_telephone;
	}

	/**
	 * @param field_type $_datecreated
	 */
	public function setDatecreated($_datecreated) {
		$this->_datecreated = $_datecreated;
	}

	/**
	 * @param field_type $_active
	 */
	public function setActive($_active) {
		$this->_active = $_active;
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