<?php

namespace Vhdang\Model;
use Vhdang\Utils\String;

class AdminOrder {
    protected $_orderno;
    protected $_admin;
    protected $_store_id;
    protected $_orderdate;
    protected $_items;
    protected $_total_web;
    protected $_discount;
    protected $_total_web1;
    protected $_tax;
    protected $_ship_us;
    protected $_total_final;
    protected $_creditcard;
    protected $_holder;
    protected $_datecreated;
    protected $_lastupdated;
    protected $_description;
    protected $_checked;
    protected $_finalized;
    
    protected $_valid;
    
    protected $_store_name;
    
    
    public function exchangeArray($data){
        
        $this->_orderno =(!empty($data['orderno'])) ? $data['orderno'] : null;
        $this->_admin =(!empty($data['admin'])) ? $data['admin'] : null;
        $this->_store_id =(!empty($data['store_id'])) ? $data['store_id'] : null;
        $this->_orderdate =(!empty($data['orderdate'])) ? $data['orderdate'] : null;
        $this->_items =(!empty($data['items'])) ? $data['items'] : null;
        $this->_total_web= (!empty($data['total_web'])) ? $data['total_web'] : null;
        $this->_discount= (!empty($data['discount'])) ? $data['discount'] : null;
        $this->_total_web1 = (!empty($data['total_web1'])) ? $data['total_web1'] : null;
        $this->_tax = (!empty($data['tax'])) ? $data['tax'] : null;
        $this->_ship_us = (!empty($data['ship_us'])) ? $data['ship_us'] : null;
        $this->_total_final = (!empty($data['total_final'])) ? $data['total_final'] : null;
        $this->_creditcard= (!empty($data['creditcard'])) ? $data['creditcard'] : null;
        $this->_holder =(!empty($data['holder'])) ? $data['holder'] : null;
        $this->_datecreated= (!empty($data['datecreated'])) ? $data['datecreated'] : null;
        $this->_lastupdated= (!empty($data['lastupdated'])) ? $data['lastupdated'] : null;
        $this->_description= (!empty($data['description'])) ? $data['description'] : null;
        $this->_checked= (!empty($data['checked'])) ? $data['checked'] : null;
        $this->_finalized= (!empty($data['finalized'])) ? $data['finalized'] : null;
        
        $this->_valid = (!empty($data['valid'])) ? $data['valid'] : null;
        
        $this->_store_name = (!empty($data['store_name'])) ? $data['store_name'] : null;
    
     
    }

	/**
	 * @return the $_finalized
	 */
	public function getFinalized() {
		return $this->_finalized;
	}

	/**
	 * @param field_type $_finalized
	 */
	public function setFinalized($_finalized) {
		$this->_finalized = $_finalized;
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
	 * @return the $_description
	 */
	public function getDescription() {
		return $this->_description;
	}

	/**
	 * @param field_type $_description
	 */
	public function setDescription($_description) {
		$this->_description = $_description;
	}

	/**
	 * @param field_type $_total_web1
	 */
	public function setTotal_web1($_total_web1) {
		$_total_web1 = String::formatNumber($_total_web1);
		$this->_total_web1 = (double) $_total_web1;		 
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
	 * @return the $_orderdate
	 */
	public function getOrderdate() {
		return $this->_orderdate;
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
	 * @return the $_store_id
	 */
	public function getStore_id() {
		return $this->_store_id;
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
	 * @return the $_discount
	 */
	public function getDiscount() {
		return $this->_discount;
	}

	/**
	 * @return the $_total_web1
	 */
	public function getTotal_web1() {    
	    
		return $this->_total_web1;
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
	 * @return the $_holder
	 */
	public function getHolder() {
		return $this->_holder;
	}

	/**
	 * @return the $_datecreated
	 */
	public function getDatecreated() {
		return $this->_datecreated;
	}

	/**
	 * @return the $_lastupdated
	 */
	public function getLastupdated() {
		return $this->_lastupdated;
	}

	
	/**
	 * @return the $_valid
	 */
	public function getValid() {
		return $this->_valid;
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
	 * @param field_type $_store_id
	 */
	public function setStore_id($_store_id) {
		$this->_store_id = $_store_id;
	}


	/**
	 * @param field_type $_items
	 */
	public function setItems($_items) {
	    
	    $_items = String::formatNumber($_items);
		
	    $this->_items = (int) $_items;
	}

	/**
	 * @param field_type $_total_web
	 */
	public function setTotal_web($_total_web) {
	    $_total_web = String::formatNumber($_total_web);
		$this->_total_web = (double) $_total_web;
	}

	/**
	 * @param field_type $_discount
	 */
	public function setDiscount($_discount) {
	    $_discount = String::formatNumber($_discount);
		$this->_discount = (double) $_discount;
	}
 

	/**
	 * @param field_type $_tax
	 */
	public function setTax($_tax) {
	    $_tax = String::formatNumber($_tax);
		$this->_tax = (double)$_tax;
	}

	/**
	 * @param field_type $_ship_us
	 */
	public function setShip_us($_ship_us) {
	    $_ship_us = String::formatNumber($_ship_us);
		$this->_ship_us = (double) $_ship_us;
	}

	/**
	 * @param field_type $_total_final
	 */
	public function setTotal_final($_total_final) {
	    $_total_final = String::formatNumber($_total_final);
		$this->_total_final = (double) $_total_final;
	}

	/**
	 * @param field_type $_holder
	 */
	public function setHolder($_holder) {
		$this->_holder = $_holder;
	}

	/**
	 * @param field_type $_datecreated
	 */
	public function setDatecreated($_datecreated) {
		$this->_datecreated = $_datecreated;
	}

	/**
	 * @param field_type $_lastupdated
	 */
	public function setLastupdated($_lastupdated) {
		
		$date = \DateTime::createFromFormat("d-m-Y", $_lastupdated);
		if ($date){
			$this->_lastupdated =  $date->format('Y-m-d');
		} else{
			$this->_lastupdated = $_lastupdated;
		}
		 
	}

	/**
	 * @param field_type $_valid
	 */
	public function setValid($_valid) {
		$this->_valid = $_valid;
	}
	
	public function isValid(){
	    $valid = true;
	    return $valid;
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