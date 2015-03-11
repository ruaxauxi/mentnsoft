<?php

namespace Vhdang\Model;

use Vhdang\Utils\String;
class ShipmentOrder {
	
	protected $_shipment_id;
	protected $_orderno;
	protected $_note;
	protected $_checked;
	protected $_package;
	protected $_items;
	protected $_total_web;
	protected $_total_web1;
	protected $_total_final;
	
	protected $_orderdate;
	protected $_store_name;
	protected $_holder;
	protected $_creditcard;
	protected $_items_o;
	protected $_discount;
	protected $_total_web_o;
	protected $_total_web1_o;
	protected $_total_final_o;
	protected $_ship_us;
	protected $_tax;
	
	
  	

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
	 * @return the $_total_final_o
	 */
	public function getTotal_final_o() {
		return $this->_total_final_o;
	}

	/**
	 * @param field_type $_total_final_o
	 */
	public function setTotal_final_o($_total_final_o) {
		$this->_total_final_o = $_total_final_o;
	}

	/**
	 * @return the $_package
	 */
	public function getPackage() {
		return $this->_package;
	}

	/**
	 * @return the $_items_o
	 */
	public function getItems_o() {
		return $this->_items_o;
	}

	/**
	 * @return the $_total_web_o
	 */
	public function getTotal_web_o() {
		return $this->_total_web_o;
	}

	/**
	 * @return the $_total_web1_o
	 */
	public function getTotal_web1_o() {
		return $this->_total_web1_o;
	}

	 
	/**
	 * @param field_type $_package
	 */
	public function setPackage($_package) {
		$this->_package = $_package;
	}

	/**
	 * @param field_type $_items_o
	 */
	public function setItems_o($_items_o) {
		$this->_items_o = $_items_o;
	}

	/**
	 * @param field_type $_total_web_o
	 */
	public function setTotal_web_o($_total_web_o) {
		$this->_total_web_o = $_total_web_o;
	}

	/**
	 * @param field_type $_total_web1_o
	 */
	public function setTotal_web1_o($_total_web1_o) {
		$this->_total_web1_o = $_total_web1_o;
	}

	 

	/**
	 * @return the $_orderdate
	 */
	public function getOrderdate() {
		return $this->_orderdate;
	}

	/**
	 * @return the $_store_name
	 */
	public function getStore_name() {
		return $this->_store_name;
	}

	/**
	 * @return the $_holder
	 */
	public function getHolder() {
		return $this->_holder;
	}

	/**
	 * @return the $_items
	 */
	public function getItems() {
		return $this->_items;
	}

	/**
	 * @return the $_discount
	 */
	public function getDiscount() {
		return $this->_discount;
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
	 * @return the $_ship_us
	 */
	public function getShip_us() {
		return $this->_ship_us;
	}

	/**
	 * @return the $_tax
	 */
	public function getTax() {
		return $this->_tax;
	}

	/**
	 * @return the $_total_final
	 */
	public function getTotal_final() {
		return $this->_total_final;
	}

	/**
	 * @param field_type $_orderdate
	 */
	public function setOrderdate($_orderdate) {
		$this->_orderdate = $_orderdate;
	}

	/**
	 * @param field_type $_store_name
	 */
	public function setStore_name($_store_name) {
		$this->_store_name = $_store_name;
	}

	/**
	 * @param field_type $_holder
	 */
	public function setHolder($_holder) {
		$this->_holder = $_holder;
	}

	/**
	 * @param field_type $_items
	 */
	public function setItems($_items) {
		$this->_items = $_items;
	}

	/**
	 * @param field_type $_discount
	 */
	public function setDiscount($_discount) {
		$this->_discount = $_discount;
	}

	/**
	 * @param field_type $_total_web
	 */
	public function setTotal_web($_total_web) {
	    $_total_web = String::formatNumber($_total_web);
		$this->_total_web = (double) $_total_web;
	}

	/**
	 * @param field_type $_total_web1
	 */
	public function setTotal_web1($_total_web1) {
	    $_total_web1 = String::formatNumber($_total_web1);
		$this->_total_web1 = (double) $_total_web1;
	}

	/**
	 * @param field_type $_ship_us
	 */
	public function setShip_us($_ship_us) {
	    $_ship_us = String::formatNumber($_ship_us);
		$this->_ship_us = (double) $_ship_us;
	}

	/**
	 * @param field_type $_tax
	 */
	public function setTax($_tax) {
	    $_tax = String::formatNumber($_tax);
		$this->_tax = (double) $_tax;
	}

	/**
	 * @param field_type $_total_final
	 */
	public function setTotal_final($_total_final) {
	    $_total_final = String::formatNumber($_total_final);
		$this->_total_final = (double) $_total_final;
	}

	/**
	 * @return the $_shipment_id
	 */
	public function getShipment_id() {
		return $this->_shipment_id;
	}

	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

	/**
	 * @return the $_note
	 */
	public function getNote() {
		return $this->_note;
	}

	/**
	 * @return the $_checked
	 */
	public function getChecked() {
		return $this->_checked;
	}

	/**
	 * @param field_type $_shipment_id
	 */
	public function setShipment_id($_shipment_id) {
		$this->_shipment_id = $_shipment_id;
	}

	/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
	}

	/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
	}

	/**
	 * @param field_type $_checked
	 */
	public function setChecked($_checked) {
		$this->_checked = $_checked;
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