<?php

namespace Vhdang\Model;
use Vhdang\Utils\String;

class AdminOrderDetails {
    
    protected $_id;
	protected $_nick;
    protected $_orderno;
    protected $_orderdate;
    protected $_description;
    protected $_items;
    protected $_discount;     
    protected $_ship_us;
    protected $_service; // %
    protected $_tax;    // number ;  default = 8.75% * total_web1 
    protected $_extra_fee;
    protected $_total_web;
    protected $_total_web1; // total_wb - discount*total_web;
    protected $_total_final; // total_web1 + tax +  ship_us + extre_fee
    protected $_note;
    protected $_status;
    protected $_checked;
    protected $_finish;
     
    protected $_store_name;
    protected $_shipment_name;
    protected $_cancelled_items;
    protected $_cancelled_total;
    protected $_package;
    
    
    public function exchangeArray($data){
        
        $this->_id = (!empty($data['id'])) ? $data['id'] : null;
        $this->_nick = (!empty($data['nick'])) ? $data['nick'] : null;
        $this->_orderno = (!empty($data['orderno'])) ? $data['orderno'] : null;
        $this->_orderdate = (!empty($data['orderdate'])) ? $data['orderdate'] : null;
        $this->_description = (!empty($data['description'])) ? $data['description'] : null; 
        $this->_items= (!empty($data['items'])) ? $data['items'] : null;
        $this->_discount =(!empty($data['discount'])) ? $data['discount'] : null;
        $this->_ship_us= (!empty($data['ship_us'])) ? $data['ship_us'] : null;
        $this->_service =(!empty($data['service'])) ? $data['service'] : null;
        $this->_tax  =(!empty($data['tax'])) ? $data['tax'] : null;
        $this->_extra_fee =(!empty($data['extra_fee'])) ? $data['extra_fee'] : null;
        $this->_total_web= (!empty($data['total_web'])) ? $data['total_web'] : null;
        $this->_total_web1 = (!empty($data['total_web1'])) ? $data['total_web1'] : null;
        $this->_total_final = (!empty($data['total_final'])) ? $data['total_final'] : null;
        $this->_note =(!empty($data['note'])) ? $data['note'] : null;
        $this->_status = (!empty($data['status'])) ? $data['status'] : null;
        $this->_checked = (!empty($data['checked'])) ? $data['checked'] : null;
        $this->_finish = (!empty($data['finish'])) ? $data['finish'] : null;
         
        $this->_store_name =(!empty($data['store_name'])) ? $data['store_name'] : null;
        $this->_shipment_name =(!empty($data['shipment_name'])) ? $data['shipment_name'] : null;
        $this->_cancelled_items= (!empty($data['cancelled_items'])) ? $data['cancelled_items'] : null;
        $this->_cancelled_total = (!empty($data['cancelled_total'])) ? $data['cancelled_total'] : null;
        $this->_package = (!empty($data['package'])) ? $data['package'] : null;
        
    }
    

	/**
	 * @return the $_finish
	 */
	public function getFinish() {
		return $this->_finish;
	}

	/**
	 * @param field_type $_finish
	 */
	public function setFinish($_finish) {
		$this->_finish = $_finish;
	}

	/**
	 * @return the $_checked
	 */
	public function getChecked() {
		return $this->_checked;
	}

	/**
	 * @param field_type $_checked
	 */
	public function setChecked($_checked) {
		$this->_checked = $_checked;
	}

	/**
	 * @return the $_package
	 */
	public function getPackage() {
		return $this->_package;
	}

	/**
	 * @param field_type $_package
	 */
	public function setPackage($_package) {
		$this->_package = $_package;
	}

	/**
	 * @return the $_cancelled_items
	 */
	public function getCancelled_items() {
		return $this->_cancelled_items;
	}

	/**
	 * @return the $_cancelled_total
	 */
	public function getCancelled_total() {
		return $this->_cancelled_total;
	}

	/**
	 * @param field_type $_cancelled_items
	 */
	public function setCancelled_items($_cancelled_items) {
	    $_cancelled_items = String::formatNumber($_cancelled_items);
		$this->_cancelled_items = $_cancelled_items;
	}

	/**
	 * @param field_type $_cancelled_total
	 */
	public function setCancelled_total($_cancelled_total) {
	    $_cancelled_total = String::formatNumber($_cancelled_total);
		$this->_cancelled_total = $_cancelled_total;
	}

	/**
	 * @return the $_shipment_name
	 */
	public function getShipment_name() {
		return $this->_shipment_name;
	}

	/**
	 * @param field_type $_shipment_name
	 */
	public function setShipment_name($_shipment_name) {
		$this->_shipment_name = $_shipment_name;
	}

	/**
	 * @return the $_status
	 */
	public function getStatus() {
		return $this->_status;
	}

	/**
	 * @param field_type $_status
	 */
	public function setStatus($_status) {
		$this->_status = $_status;
	}

	/**
	 * @return the $_store_name
	 */
	public function getStore_name() {
		return $this->_store_name;
	}

	/**
	 * @param field_type $_store_name
	 */
	public function setStore_name($_store_name) {
		$this->_store_name = $_store_name;
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

	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

	/**
	 * @return the $_orderdate
	 */
	public function getOrderdate() {
		return $this->_orderdate;
	}

	/**
	 * @return the $_description
	 */
	public function getDescription() {
		return $this->_description;
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
	 * @return the $_ship_us
	 */
	public function getShip_us() {
		return $this->_ship_us;
	}

	/**
	 * @return the $_service
	 */
	public function getService() {
		return $this->_service;
	}

	/**
	 * @return the $_tax
	 */
	public function getTax() {
		return $this->_tax;
	}

	/**
	 * @return the $_extra_fee
	 */
	public function getExtra_fee() {
		return $this->_extra_fee;
	}

	/**
	 * @return the $_total
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
	 * @return the $_total_final
	 */
	public function getTotal_final() {	   
		return $this->_total_final;
	}

	/**
	 * @return the $_note
	 */
	public function getNote() {
		return $this->_note;
	}

	/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
	}

	/**
	 * @param field_type $_orderdate
	 */
	public function setOrderdate($_orderdate) {
	    $date = \DateTime::createFromFormat("d-m-Y", $_orderdate);
	    if ($date){
	    	$this->_orderdate =  $date->format('Y-m-d');
	    } else{
	    	$this->_orderdate = $_orderdate;
	    }
		 
	}

	/**
	 * @param field_type $_description
	 */
	public function setDescription($_description) {
		$this->_description = $_description;
	}

	/**
	 * @param field_type $_items
	 */
	public function setItems($_items) {	    
	    $_items = String::formatNumber($_items);	    
	    $this->_items = (int) $_items;
	}

	/**
	 * @param field_type $_discount
	 */
	public function setDiscount($_discount) {
	    $_discount = String::formatNumber($_discount);
	    $this->_discount = (double) $_discount;	    
	}

	 

	/**
	 * @param field_type $_ship_us
	 */
	public function setShip_us($_ship_us) {
	    $_ship_us = String::formatNumber($_ship_us);
		$this->_ship_us = (double) $_ship_us;
	}

	/**
	 * @param field_type $_service
	 */
	public function setService($_service) {
	    $_service = String::formatNumber($_service);
		$this->_service = (double) $_service;
	}

	/**
	 * @param field_type $_tax
	 */
	public function setTax($_tax) {
	    $_tax = String::formatNumber($_tax);
		$this->_tax = (double) $_tax;
	}

	/**
	 * @param field_type $_extra_fee
	 */
	public function setExtra_fee($_extra_fee) {
	    $_extra_fee = String::formatNumber($_extra_fee);
		$this->_extra_fee = (double) $_extra_fee;
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
	 * @param field_type $_total_final
	 */
	public function setTotal_final($_total_final) {
	    $_total_final =  String::formatNumber($_total_final);
		$this->_total_final = (double) $_total_final;
	}

	/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
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