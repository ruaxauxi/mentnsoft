<?php
namespace Vhdang\Model;

class ViewShippingFee{
    
    protected $_shipment_id;
    protected $_shipment_name;
    protected $_weight;
    protected $_total;  
     
    
    
	/**
	 * @return the $_weight
	 */
	public function getWeight() {
		return $this->_weight;
	}

	/**
	 * @param field_type $_weight
	 */
	public function setWeight($_weight) {
		$this->_weight = $_weight;
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