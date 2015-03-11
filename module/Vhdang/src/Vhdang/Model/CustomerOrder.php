<?php

namespace Vhdang\Model;

class CustomerOrder {
	
    protected $_id;
    protected $_store_id;
    protected $_nick;
    protected $_datecreated;
    protected $_approveddate;
    protected $_note;
    protected $_description;
    protected $_status;
    protected $_approvedby;
    
    protected $_store_name;
    protected $_balance;
    protected $_wait_confirm;
    
   

	public function exchangeArray($data){
    	$this->_id     = (!empty($data['id'])) ? $data['id'] : null;
    	$this->_store_id = (!empty($data['store_id'])) ? $data['store_id'] : null;
    	$this->_nick = (!empty($data['nick'])) ? $data['nick'] : null;
    	$this->_datecreated = (!empty($data['datecreated'])) ? $data['datecreated'] : null;
    	$this->_approveddate = (!empty($data['approvedate'])) ? $data['approvedate'] : null;
    	$this->_note = (!empty($data['note'])) ? $data['note'] : null;
    	$this->_description = (!empty($data['description'])) ? $data['description'] : null;
    	$this->_status = (!empty($data['status'])) ? $data['status'] : null;
    	$this->_approvedby = (!empty($data['approvedby'])) ? $data['approvedby'] : null;
    	$this->_store_name = (!empty($data['store_name'])) ? $data['store_name'] : null;
    	
    	$this->_balance = (!empty($data['balance'])) ? $data['balance'] : null;
    	$this->_wait_confirm = (!empty($data['wait_confirm'])) ? $data['wait_confirm'] : null;
    }
    
    
    /**
     * @return the $_balance
     */
    public function getBalance() {
    	return $this->_balance;
    }
    
    /**
     * @return the $_wait_confirm
     */
    public function getWait_confirm() {
    	return $this->_wait_confirm;
    }
    
    /**
     * @param field_type $_balance
     */
    public function setBalance($_balance) {
    	$this->_balance = $_balance;
    }
    
    /**
     * @param field_type $_wait_confirm
     */
    public function setWait_confirm($_wait_confirm) {
    	$this->_wait_confirm = $_wait_confirm;
    }
	
	/**
	 * @return the $_approveddate
	 */
	public function getApproveddate() {
		return $this->_approveddate;
	}

	/**
	 * @param field_type $_approveddate
	 */
	public function setApproveddate($_approveddate) {
		$this->_approveddate = $_approveddate;
	}

	/**
	 * @return the $_approvedby
	 */
	public function getApprovedby() {
		return $this->_approvedby;
	}

	/**
	 * @param field_type $_approvedby
	 */
	public function setApprovedby($_approvedby) {
		$this->_approvedby = $_approvedby;
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
	 * @return the $_note
	 */
	public function getNote() {
		return $this->_note;
	}

	/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_store_id
	 */
	public function getStore_id() {
		return $this->_store_id;
	}

	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @return the $_datecreated
	 */
	public function getDatecreated() {
		return $this->_datecreated;
	}
 
	/**
	 * @return the $_description
	 */
	public function getDescription() {
		return $this->_description;
	}

	/**
	 * @return the $_status
	 */
	public function getStatus() {
		return $this->_status;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_store_id
	 */
	public function setStore_id($_store_id) {
		$this->_store_id = $_store_id;
	}

	/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @param field_type $_datecreated
	 */
	public function setDatecreated($_datecreated) {
		$this->_datecreated = $_datecreated;
	}

 

	/**
	 * @param field_type $_description
	 */
	public function setDescription($_description) {
		$this->_description = $_description;
	}

	/**
	 * @param field_type $_status
	 */
	public function setStatus($_status) {
		$this->_status = $_status;
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