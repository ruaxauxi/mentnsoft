<?php

namespace Vhdang\Model;

class Transaction {
	
    protected $_id;
    protected $_admin;
    protected $_balance_id;
    protected $_trans_date;
    protected $_orderno;
    protected $_order_detail_id;
    protected $_transfer_id;
    protected $_shipment_id;
    protected $_date;
    protected $_type;
    protected $_amount;
    protected $_credit;
    protected $_note;
    
    protected $_nick;
    protected $_check;
    
    
    /**
	 * @return the $_orderno
	 */
	public function getOrderno() {
		return $this->_orderno;
	}

	/**
	 * @return the $_check
	 */
	public function getCheck() {
		return $this->_check;
	}

	/**
	 * @param field_type $_orderno
	 */
	public function setOrderno($_orderno) {
		$this->_orderno = $_orderno;
	}

	/**
	 * @param field_type $_check
	 */
	public function setCheck($_check) {
		$this->_check = $_check;
	}

	public function exchangeArray($data){
    	$this->_id     = (!empty($data['id'])) ? $data['id'] : null;   
    	$this->_admin = (!empty($data['admin'])) ? $data['admin'] : null;
    	$this->_balance_id = (!empty($data['balance_id'])) ? $data['balance_id'] : null;
    	$this->_trans_date = (!empty($data['trans_date'])) ? $data['trans_date'] : null;
    	$this->_orderno = (!empty($data['orderno'])) ? $data['orderno'] : null;
    	$this->_order_detail_id = (!empty($data['order_detail_id'])) ? $data['order_detail_id'] : null;
    	$this->_transfer_id = (!empty($data['transfer_id'])) ? $data['transfer_id'] : null;
    	$this->_shipment_id = (!empty($data['shipment_id'])) ? $data['shipment_id'] : null;
    	$this->_date = (!empty($data['date'])) ? $data['date'] : null;
    	$this->_type = (!empty($data['type'])) ? $data['type'] : null;    	
    	$this->_amount = (!empty($data['amount'])) ? $data['amount'] : null;
    	$this->_credit = (!empty($data['credit'])) ? $data['credit'] : null;
    	$this->_note = (!empty($data['note'])) ? $data['note'] : null;
    	$this->_nick = (!empty($data['nick'])) ? $data['nick'] : null;
    	$this->_check = (!empty($data['check'])) ? $data['check'] : null;
    	 
    }
    
    
    
	/**
	 * @return the $_shipment_id
	 */
	public function getShipment_id() {
		return $this->_shipment_id;
	}

	/**
	 * @param field_type $_shipment_id
	 */
	public function setShipment_id($_shipment_id) {
		$this->_shipment_id = $_shipment_id;
	}

	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @return the $_transfer_id
	 */
	public function getTransfer_id() {
		return $this->_transfer_id;
	}

	/**
	 * @param field_type $_transfer_id
	 */
	public function setTransfer_id($_transfer_id) {
		$this->_transfer_id = $_transfer_id;
	}

	/**
	 * @return the $_order_detail_id
	 */
	public function getOrder_detail_id() {
		return $this->_order_detail_id;
	}

	/**
	 * @param field_type $_order_detail_id
	 */
	public function setOrder_detail_id($_order_detail_id) {
		$this->_order_detail_id = $_order_detail_id;
	}

	/**
	 * @return the $_date
	 */
	public function getDate() {
		return $this->_date;
	}

	/**
	 * @param field_type $_date
	 */
	public function setDate($_date) {
		$this->_date = $_date;
	}

	/**
	 * @return the $_credit
	 */
	public function getCredit() {
		return $this->_credit;
	}

	/**
	 * @param field_type $_credit
	 */
	public function setCredit($_credit) {
		$this->_credit = $_credit;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_admin
	 */
	public function getAdmin() {
		return $this->_admin;
	}

	/**
	 * @return the $_balance_id
	 */
	public function getBalance_id() {
		return $this->_balance_id;
	}

	/**
	 * @return the $_trans_date
	 */
	public function getTrans_date() {
		return $this->_trans_date;
	}

	/**
	 * @return the $_type
	 */
	public function getType() {
		return $this->_type;
	}

	/**
	 * @return the $_amount
	 */
	public function getAmount() {
		return $this->_amount;
	}

	/**
	 * @return the $_note
	 */
	public function getNote() {
		return $this->_note;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_admin
	 */
	public function setAdmin($_admin) {
		$this->_admin = $_admin;
	}

	/**
	 * @param field_type $_balance_id
	 */
	public function setBalance_id($_balance_id) {
		$this->_balance_id = $_balance_id;
	}

	/**
	 * @param field_type $_trans_date
	 */
	public function setTrans_date($_trans_date) {
		$this->_trans_date = $_trans_date;
	}

	/**
	 * @param field_type $_type
	 */
	public function setType($_type) {
		$this->_type = $_type;
	}

	/**
	 * @param field_type $_amount
	 */
	public function setAmount($_amount) {
		$this->_amount = $_amount;
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