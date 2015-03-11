<?php
namespace Vhdang\Model;

class Summarize{
    
    protected $_shipment_id;
    protected $_shipment_name;
    protected $_tongthukh;
    protected $_tongthushipping;
    protected $_tongthuweight;
    
    protected $_lotamtinh;
    protected $_tongtienhang;
    protected $_tongshipping;
    protected $_tongshippingweigh;
    protected $_tongchiphi;
    protected $_tonggiaodichkhac;
    protected $_tongdung;
    protected $_total;
    protected $_paid;
     

	/**
	 * @return the $_paid
	 */
	public function getPaid() {
		return $this->_paid;
	}

	/**
	 * @param field_type $_paid
	 */
	public function setPaid($_paid) {
		$this->_paid = $_paid;
	}

	/**
	 * @return the $_tongthuweight
	 */
	public function getTongthuweight() {
		return $this->_tongthuweight;
	}

	/**
	 * @return the $_tongshippingweigh
	 */
	public function getTongshippingweigh() {
		return $this->_tongshippingweigh;
	}

	/**
	 * @param field_type $_tongthuweight
	 */
	public function setTongthuweight($_tongthuweight) {
		$this->_tongthuweight = $_tongthuweight;
	}

	/**
	 * @param field_type $_tongshippingweigh
	 */
	public function setTongshippingweigh($_tongshippingweigh) {
		$this->_tongshippingweigh = $_tongshippingweigh;
	}

	/**
	 * @return the $_total
	 */
	public function getTotal() {
		return $this->_total;
	}

	/**
	 * @param field_type $_total
	 */
	public function setTotal($_total) {
		$this->_total = $_total;
	}

	/**
	 * @return the $_tongdung
	 */
	public function getTongdung() {
		return $this->_tongdung;
	}

	/**
	 * @param field_type $_tongdung
	 */
	public function setTongdung($_tongdung) {
		$this->_tongdung = $_tongdung;
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
	 * @return the $_tongthukh
	 */
	public function getTongthukh() {
		return $this->_tongthukh;
	}

	/**
	 * @return the $_tongthushipping
	 */
	public function getTongthushipping() {
		return $this->_tongthushipping;
	}

	/**
	 * @return the $_lotamtinh
	 */
	public function getLotamtinh() {
		return $this->_lotamtinh;
	}

	/**
	 * @return the $_tongtienhang
	 */
	public function getTongtienhang() {
		return $this->_tongtienhang;
	}

	/**
	 * @return the $_tongshipping
	 */
	public function getTongshipping() {
		return $this->_tongshipping;
	}

	/**
	 * @return the $_tongchiphi
	 */
	public function getTongchiphi() {
		return $this->_tongchiphi;
	}

	/**
	 * @return the $tonggiaodichkhac
	 */
	public function getTonggiaodichkhac() {
		return $this->_tonggiaodichkhac;
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
	 * @param field_type $_tongthukh
	 */
	public function setTongthukh($_tongthukh) {
		$this->_tongthukh = $_tongthukh;
	}

	/**
	 * @param field_type $_tongthushipping
	 */
	public function setTongthushipping($_tongthushipping) {
		$this->_tongthushipping = $_tongthushipping;
	}

	/**
	 * @param field_type $_lotamtinh
	 */
	public function setLotamtinh($_lotamtinh) {
		$this->_lotamtinh = $_lotamtinh;
	}

	/**
	 * @param field_type $_tongtienhang
	 */
	public function setTongtienhang($_tongtienhang) {
		$this->_tongtienhang = $_tongtienhang;
	}

	/**
	 * @param field_type $_tongshipping
	 */
	public function setTongshipping($_tongshipping) {
		$this->_tongshipping = $_tongshipping;
	}

	/**
	 * @param field_type $_tongchiphi
	 */
	public function setTongchiphi($_tongchiphi) {
		$this->_tongchiphi = $_tongchiphi;
	}

	/**
	 * @param field_type $tonggiaodichkhac
	 */
	public function setTonggiaodichkhac($_tonggiaodichkhac) {
		$this->_tonggiaodichkhac = $_tonggiaodichkhac;
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