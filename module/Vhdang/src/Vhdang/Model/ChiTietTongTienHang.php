<?php
namespace Vhdang\Model;

class ChiTietTongTienHang{
    
    protected $_orderno;
    protected $_total;
    protected $_cancel;
    protected $_total_final;
     
	/**
	 * @return the $_total_final
	 */
	public function getTotal_final() {
		return $this->_total_final;
	}

	/**
	 * @param field_type $_total_final
	 */
	public function setTotal_final($_total_final) {
		$this->_total_final = $_total_final;
	}

	/**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
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
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
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