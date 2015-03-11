<?php

namespace Vhdang\Model;


class OrderFinalizing{
    
    protected $_orderno;
    protected $_admin;
    protected $_orderdate;
    protected $_store_id;
    protected $_store_name;
    protected $_items;
    protected $_total_web;
    protected $_total_web1;
    protected $_discount;
    protected $_tax;
    protected $_ship_us;
    protected $_total_final;
    protected $_creditcard;
    protected $_holder;
     
    protected $_cancelled_items;
    protected $_total_cancelled;
    protected $_paid;
    
    
	 
	 
	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

	/**
	 * @return the $_admin
	 */
	public function getAdmin() {
		return $this->_admin;
	}

	/**
	 * @return the $_orderdate
	 */
	public function getOrderdate() {
		return $this->_orderdate;
	}

	/**
	 * @return the $_store_id
	 */
	public function getStore_id() {
		return $this->_store_id;
	}

	/**
	 * @return the $_store_name
	 */
	public function getStore_name() {
		return $this->_store_name;
	}

	/**
	 * @return the $_items
	 */
	public function getItems() {
		return $this->_items;
	}

	/**
	 * @return the $_total_web
	 */
	public function getTotal_web() {
		return $this->_total_web;
	}

	/**
	 * @return the $_total_web1
	 */
	public function getTotal_web1() {
		return $this->_total_web1;
	}

	/**
	 * @return the $_discount
	 */
	public function getDiscount() {
		return $this->_discount;
	}

	/**
	 * @return the $_tax
	 */
	public function getTax() {
		return $this->_tax;
	}

	/**
	 * @return the $_ship_us
	 */
	public function getShip_us() {
		return $this->_ship_us;
	}

	/**
	 * @return the $_total_final
	 */
	public function getTotal_final() {
		return $this->_total_final;
	}

	/**
	 * @return the $_creditcard
	 */
	public function getCreditcard() {
		return $this->_creditcard;
	}

	/**
	 * @return the $_holder
	 */
	public function getHolder() {
		return $this->_holder;
	}

	/**
	 * @return the $_cancelled_items
	 */
	public function getCancelled_items() {
		return $this->_cancelled_items;
	}

	/**
	 * @return the $_total_cancelled
	 */
	public function getTotal_cancelled() {
		return $this->_total_cancelled;
	}

	/**
	 * @return the $_paid
	 */
	public function getPaid() {
		return $this->_paid;
	}

	/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
	}

	/**
	 * @param field_type $_admin
	 */
	public function setAdmin($_admin) {
		$this->_admin = $_admin;
	}

	/**
	 * @param field_type $_orderdate
	 */
	public function setOrderdate($_orderdate) {
		$this->_orderdate = $_orderdate;
	}

	/**
	 * @param field_type $_store_id
	 */
	public function setStore_id($_store_id) {
		$this->_store_id = $_store_id;
	}

	/**
	 * @param field_type $_store_name
	 */
	public function setStore_name($_store_name) {
		$this->_store_name = $_store_name;
	}

	/**
	 * @param field_type $_items
	 */
	public function setItems($_items) {
		$this->_items = $_items;
	}

	/**
	 * @param field_type $_total_web
	 */
	public function setTotal_web($_total_web) {
		$this->_total_web = $_total_web;
	}

	/**
	 * @param field_type $_total_web1
	 */
	public function setTotal_web1($_total_web1) {
		$this->_total_web1 = $_total_web1;
	}

	/**
	 * @param field_type $_discount
	 */
	public function setDiscount($_discount) {
		$this->_discount = $_discount;
	}

	/**
	 * @param field_type $_tax
	 */
	public function setTax($_tax) {
		$this->_tax = $_tax;
	}

	/**
	 * @param field_type $_ship_us
	 */
	public function setShip_us($_ship_us) {
		$this->_ship_us = $_ship_us;
	}

	/**
	 * @param field_type $_total_final
	 */
	public function setTotal_final($_total_final) {
		$this->_total_final = $_total_final;
	}

	/**
	 * @param field_type $_creditcard
	 */
	public function setCreditcard($_creditcard) {
		$this->_creditcard = $_creditcard;
	}

	/**
	 * @param field_type $_holder
	 */
	public function setHolder($_holder) {
		$this->_holder = $_holder;
	}

	/**
	 * @param field_type $_cancelled_items
	 */
	public function setCancelled_items($_cancelled_items) {
		$this->_cancelled_items = $_cancelled_items;
	}

	/**
	 * @param field_type $_total_cancelled
	 */
	public function setTotal_cancelled($_total_cancelled) {
		$this->_total_cancelled = $_total_cancelled;
	}

	/**
	 * @param field_type $_paid
	 */
	public function setPaid($_paid) {
		$this->_paid = $_paid;
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