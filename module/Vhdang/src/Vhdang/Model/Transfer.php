<?php

namespace Vhdang\Model;

class Transfer {
	
    protected $_id;    
    protected $_nick;    
    protected $_x_rate;
    protected $_trans_date;
    protected $_datecreated;
    protected $_refno;
    protected $_trans_type;
    protected $_vnd;
    protected $_usd;
    protected $_note;
    protected $_status;
    
    protected $_error;
    
    /**
	 * @return the $_trans_type
	 */
	public function getTrans_type() {
		return $this->_trans_type;
	}

	/**
	 * @param field_type $_trans_type
	 */
	public function setTrans_type($_trans_type) {
		$this->_trans_type = $_trans_type;
	}

	/**
	 * @return the $_refno
	 */
	public function getRefno() {
		return $this->_refno;
	}

	/**
	 * @param field_type $_refno
	 */
	public function setRefno($_refno) {
		$this->_refno = $_refno;
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

	public function getError($key){
    	if (isset($this->_error[$key])){
    		$error = sprintf("<span class='error'>%s</span>",$this->_error[$key]);
    		return $error;
    	}else{
    		return null;
    	}
    }
    
    public function setError($key,$str){
    	$this->_error[$key]  = $str;
    }

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	 
	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @return the $_x_rate
	 */
	public function getX_rate() {
		return $this->_x_rate;
	}

	/**
	 * @return the $_trans_date
	 */
	public function getTrans_date() {
		return $this->_trans_date;
	}

	/**
	 * @return the $_datecreated
	 */
	public function getDatecreated() {
		return $this->_datecreated;
	}

	
	/**
	 * @return the $_vnd
	 */
	public function getVnd() {
		return $this->_vnd;
	}

	/**
	 * @return the $_usd
	 */
	public function getUsd() {
	    if ($this->_vnd && $this->_x_rate){
	        $usd = round($this->_vnd/$this->_x_rate,2);
	        return $usd;
	    }else{
	        return 0;
	    }
	   
		
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
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @param field_type $_x_rate
	 */
	public function setX_rate($_x_rate) {
		$this->_x_rate = $_x_rate;
	}

	/**
	 * @param field_type $_trans_date
	 */
	public function setTrans_date($_trans_date) {
	    $date = \DateTime::createFromFormat("d-m-Y", $_trans_date);	
	    if ($date){
	        $this->_trans_date =  $date->format('Y-m-d');
	    } else{
	        $this->_trans_date = $_trans_date;
	    }    
		
	}

	/**
	 * @param field_type $_datecreated
	 */
	public function setDatecreated($_datecreated) {
		$this->_datecreated = $_datecreated;
	}

	
	/**
	 * @param field_type $_vnd
	 */
	public function setVnd($_vnd) {
	    $_vnd = str_replace(',', '', $_vnd);
		$this->_vnd = $_vnd;
	}

	/**
	 * @param field_type $_usd
	 */
	public function setUsd($_usd) {
		$this->_usd = $_usd;
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
	
	public function validDateFormat($strdate){
		if (empty($strdate) || $strdate == null){			 
			return null;
		}
		$pattern = '/^[0-9]{1,2}[\-\/][0-9]{1,2}[\-\/][12][0-9]{3}$/';
		if (!preg_match($pattern, $strdate)){
			return null;
		}
		 
		$date = str_replace('/', '-', $strdate);
		 
		$date = explode('-',$date);
		$d = (int)$date[0];
		$m = (int)$date[1];
		$y = (int)$date[2];
		 
		if (!checkdate($m,$d,$y)){
			return null;
		}
		 
		$date = sprintf("%02d-%02d-%04d",$d,$m,$y);// dd-mm-yyyy
		$newDate = new \DateTime();
		$newDate = $newDate->createFromFormat('d-m-Y', $date);
		if ($newDate){
			return  $newDate->format('d-m-Y');
		}else{
			return null;
		}
		 
		 
	}
	
	public function isValid(){
		$valid = true;
					
		return $valid;
	}
    
}