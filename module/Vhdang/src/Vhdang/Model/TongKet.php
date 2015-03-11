<?php

namespace Vhdang\Model;

class TongKet{
    
    protected $_shipment_id;
    protected $_tongthu_kh;
    protected $_shipping_fee_kg;
    protected $_shipping_fee_usd;
    protected $_linhtinh;
    protected $_tienhang;
    protected $_tongshipping_kg;
    protected $_tongshipping_usd;
    protected $_tongchiphi;
    protected $_giaodichkhac;
    protected $_tongdung;
    protected $_sum;
    protected $_date;
    protected $_admin; 
    

	/**
	 * @return the $_shipment_id
	 */
	public function getShipment_id() {
		return $this->_shipment_id;
	}

	/**
	 * @return the $_tongthu_kh
	 */
	public function getTongthu_kh() {
		return $this->_tongthu_kh;
	}

	/**
	 * @return the $_shipping_fee_kg
	 */
	public function getShipping_fee_kg() {
		return $this->_shipping_fee_kg;
	}

	/**
	 * @return the $_shipping_fee_usd
	 */
	public function getShipping_fee_usd() {
		return $this->_shipping_fee_usd;
	}

	/**
	 * @return the $_linhtinh
	 */
	public function getLinhtinh() {
		return $this->_linhtinh;
	}

	/**
	 * @return the $_tienhang
	 */
	public function getTienhang() {
		return $this->_tienhang;
	}

	/**
	 * @return the $_tongshipping_kg
	 */
	public function getTongshipping_kg() {
		return $this->_tongshipping_kg;
	}

	/**
	 * @return the $_tongshipping_usd
	 */
	public function getTongshipping_usd() {
		return $this->_tongshipping_usd;
	}

	/**
	 * @return the $_tongchiphi
	 */
	public function getTongchiphi() {
		return $this->_tongchiphi;
	}

	/**
	 * @return the $_giaodichkhac
	 */
	public function getGiaodichkhac() {
		return $this->_giaodichkhac;
	}

	/**
	 * @return the $_tongdung
	 */
	public function getTongdung() {
		return $this->_tongdung;
	}

	/**
	 * @return the $_sum
	 */
	public function getSum() {
		return $this->_sum;
	}

	/**
	 * @return the $_date
	 */
	public function getDate() {
		return $this->_date;
	}

	/**
	 * @return the $_admin
	 */
	public function getAdmin() {
		return $this->_admin;
	}

	/**
	 * @param field_type $_shipment_id
	 */
	public function setShipment_id($_shipment_id) {
		$this->_shipment_id = $_shipment_id;
	}

	/**
	 * @param field_type $_tongthu_kh
	 */
	public function setTongthu_kh($_tongthu_kh) {
		$this->_tongthu_kh = $_tongthu_kh;
	}

	/**
	 * @param field_type $_shipping_fee_kg
	 */
	public function setShipping_fee_kg($_shipping_fee_kg) {
		$this->_shipping_fee_kg = $_shipping_fee_kg;
	}

	/**
	 * @param field_type $_shipping_fee_usd
	 */
	public function setShipping_fee_usd($_shipping_fee_usd) {
		$this->_shipping_fee_usd = $_shipping_fee_usd;
	}

	/**
	 * @param field_type $_linhtinh
	 */
	public function setLinhtinh($_linhtinh) {
		$this->_linhtinh = $_linhtinh;
	}

	/**
	 * @param field_type $_tienhang
	 */
	public function setTienhang($_tienhang) {
		$this->_tienhang = $_tienhang;
	}

	/**
	 * @param field_type $_tongshipping_kg
	 */
	public function setTongshipping_kg($_tongshipping_kg) {
		$this->_tongshipping_kg = $_tongshipping_kg;
	}

	/**
	 * @param field_type $_tongshipping_usd
	 */
	public function setTongshipping_usd($_tongshipping_usd) {
		$this->_tongshipping_usd = $_tongshipping_usd;
	}

	/**
	 * @param field_type $_tongchiphi
	 */
	public function setTongchiphi($_tongchiphi) {
		$this->_tongchiphi = $_tongchiphi;
	}

	/**
	 * @param field_type $_giaodichkhac
	 */
	public function setGiaodichkhac($_giaodichkhac) {
		$this->_giaodichkhac = $_giaodichkhac;
	}

	/**
	 * @param field_type $_tongdung
	 */
	public function setTongdung($_tongdung) {
		$this->_tongdung = $_tongdung;
	}

	/**
	 * @param field_type $_sum
	 */
	public function setSum($_sum) {
		$this->_sum = $_sum;
	}

	/**
	 * @param field_type $_date
	 */
	public function setDate($_date) {
		$this->_date = $_date;
	}

	/**
	 * @param field_type $_admin
	 */
	public function setAdmin($_admin) {
		$this->_admin = $_admin;
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