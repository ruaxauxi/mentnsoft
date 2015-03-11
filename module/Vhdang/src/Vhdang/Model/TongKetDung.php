<?php

namespace Vhdang\Model;

class TongKetDung{
    
    protected $_shipment_id;
    protected $_shipment_name;
    protected $_total_web1; // sum(orders) - total(cancelled items + cancelled orders);
    protected $_service; // dung's services = total_web1 * 3%;
    protected $_shipping; // shipping weight * 0.6;
    protected $_shippingweight;
    
    protected $_totalItemCancel;
    protected $_totalOrderCancel;
    protected $_total; // = service + shipping;
    
    protected $_orderno;


	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

	/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
	}

	/**
	 * @return the $_shippingweight
	 */
	public function getShippingweight() {
		return $this->_shippingweight;
	}

	/**
	 * @param field_type $_shippingweight
	 */
	public function setShippingweight($_shippingweight) {
		$this->_shippingweight = $_shippingweight;
	}

	/**
	 * @return the $_totalItemCancel
	 */
	public function getTotalItemCancel() {
		return $this->_totalItemCancel;
	}

	/**
	 * @return the $_totalOrderCancel
	 */
	public function getTotalOrderCancel() {
		return $this->_totalOrderCancel;
	}

 
	/**
	 * @param field_type $_totalItemCancel
	 */
	public function setTotalItemCancel($_totalItemCancel) {
		$this->_totalItemCancel = $_totalItemCancel;
	}

	/**
	 * @param field_type $_totalOrderCancel
	 */
	public function setTotalOrderCancel($_totalOrderCancel) {
		$this->_totalOrderCancel = $_totalOrderCancel;
	}

 

	/**
	 * @return the $_shipment_id
	 */
	public function getShipment_id() {
		return $this->_shipment_id;
	}

	/**
	 * @return the $_shipment_name
	 */
	public function getShipment_name() {
		return $this->_shipment_name;
	}

	/**
	 * @return the $_total_web1
	 */
	public function getTotal_web1() {
		return $this->_total_web1;
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
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

	/**
	 * @param field_type $_shipment_id
	 */
	public function setShipment_id($_shipment_id) {
		$this->_shipment_id = $_shipment_id;
	}

	/**
	 * @param field_type $_shipment_name
	 */
	public function setShipment_name($_shipment_name) {
		$this->_shipment_name = $_shipment_name;
	}

	/**
	 * @param field_type $_total_web1
	 */
	public function setTotal_web1($_total_web1) {
		$this->_total_web1 = $_total_web1;
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
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
		$this->_total = $_total;
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