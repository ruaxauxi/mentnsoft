<?php
namespace Vhdang\Model;

class TongThuKH{
    
    protected $_shipment_id;
    protected $_shipment_name;
    protected $_total; // Tong thu trong cac orders
    protected $_cancel; // tong cancel
    protected $_total_final;  // total - cancel
    
    protected $_shipping_fee; // total shipping fee
    protected $_lotamtinh; // lo tam tinh khi nhap vao tk linhtinh
    
    
    /**
	 * @return the $_lotamtinh
	 */
	public function getLotamtinh() {
		return $this->_lotamtinh;
	}

	/**
	 * @param field_type $_lotamtinh
	 */
	public function setLotamtinh($_lotamtinh) {
		$this->_lotamtinh = $_lotamtinh;
	}

	/**
	 * @return the $_shipping_fee
	 */
	public function getShipping_fee() {
		return $this->_shipping_fee;
	}

	/**
	 * @param field_type $_shipping_fee
	 */
	public function setShipping_fee($_shipping_fee) {
		$this->_shipping_fee = $_shipping_fee;
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
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

	/**
	 * @return the $_cancel
	 */
	public function getCancel() {
		return $this->_cancel;
	}

	/**
	 * @return the $_total_final
	 */
	public function getTotal_final() {
		return $this->_total_final;
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
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
		$this->_total = $_total;
	}

	/**
	 * @param field_type $_cancel
	 */
	public function setCancel($_cancel) {
		$this->_cancel = $_cancel;
	}

	/**
	 * @param field_type $_total_final
	 */
	public function setTotal_final($_total_final) {
		$this->_total_final = $_total_final;
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