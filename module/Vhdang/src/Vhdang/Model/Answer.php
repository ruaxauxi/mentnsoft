<?php

namespace Vhdang\Model;

class Answer{
    
    protected $_id;
    protected $_question_id;
    protected $_content;
    protected $_nick;
    protected $_admin;
    protected $_datecreated;
    
    
	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_question_id
	 */
	public function getQuestion_id() {
		return $this->_question_id;
	}

	/**
	 * @return the $_content
	 */
	public function getContent() {
		return $this->_content;
	}

	/**
	 * @return the $_nick
	 */
	public function getNick() {
		return $this->_nick;
	}

	/**
	 * @return the $_admin
	 */
	public function getAdmin() {
		return $this->_admin;
	}

	/**
	 * @return the $_datecreated
	 */
	public function getDatecreated() {
		return $this->_datecreated;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_question_id
	 */
	public function setQuestion_id($_question_id) {
		$this->_question_id = $_question_id;
	}

	/**
	 * @param field_type $_content
	 */
	public function setContent($_content) {
		$this->_content = $_content;
	}

	/**
	 * @param field_type $_nick
	 */
	public function setNick($_nick) {
		$this->_nick = $_nick;
	}

	/**
	 * @param field_type $_admin
	 */
	public function setAdmin($_admin) {
		$this->_admin = $_admin;
	}

	/**
	 * @param field_type $_datecreated
	 */
	public function setDatecreated($_datecreated) {
	   $date = \DateTime::createFromFormat("d-m-Y", $_datecreated);
	    if ($date){
	    	$this->_datecreated =  $date->format('Y-m-d');
	    } else{
	    	$this->_datecreated = $_datecreated;
	    }
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