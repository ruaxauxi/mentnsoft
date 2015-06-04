<?php

namespace Vhdang\Model;

use Vhdang\Utils\String;
class ExpressShippingFee {
	
    protected $_id;
    protected $_shipment_id;
    protected $_admin;
    protected $_nick; 
    protected $_fee;
    protected $_usd;
    protected $_xrate;
    protected $_note;
    protected $_date;
    
    

	/**
     * @return the $_usd
     */
    public function getUsd()
    {
        return $this->_usd;
    }

 /**
     * @return the $_xrate
     */
    public function getXrate()
    {
        return $this->_xrate;
    }

 /**
     * @param field_type $_usd
     */
    public function setUsd($_usd)
    {
        $this->_usd = $_usd;
    }

 /**
     * @param field_type $_xrate
     */
    public function setXrate($_xrate)
    {
        $this->_xrate = $_xrate;
    }

 /**
     * @return the $_fee
     */
    public function getFee()
    {
        return $this->_fee;
    }

 /**
     * @param field_type $_fee
     */
    public function setFee($_fee)
    {
        $this->_fee = $_fee;
    }

 /**
     * @return the $_id
     */
    public function getId()
    {
        return $this->_id;
    }

 /**
     * @return the $_shipment_id
     */
    public function getShipment_id()
    {
        return $this->_shipment_id;
    }

 /**
     * @return the $_admin
     */
    public function getAdmin()
    {
        return $this->_admin;
    }

 /**
     * @return the $_nick
     */
    public function getNick()
    {
        return $this->_nick;
    }

 /**
     * @return the $_note
     */
    public function getNote()
    {
        return $this->_note;
    }

 /**
     * @return the $_date
     */
    public function getDate()
    {
        return $this->_date;
    }

 /**
     * @param field_type $_id
     */
    public function setId($_id)
    {
        $this->_id = $_id;
    }

 /**
     * @param field_type $_shipment_id
     */
    public function setShipment_id($_shipment_id)
    {
        $this->_shipment_id = $_shipment_id;
    }

 /**
     * @param field_type $_admin
     */
    public function setAdmin($_admin)
    {
        $this->_admin = $_admin;
    }

 /**
     * @param field_type $_nick
     */
    public function setNick($_nick)
    {
        $this->_nick = $_nick;
    }

 /**
     * @param field_type $_note
     */
    public function setNote($_note)
    {
        $this->_note = $_note;
    }

 /**
     * @param field_type $_date
     */
    public function setDate($_date)
    {
        $this->_date = $_date;
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