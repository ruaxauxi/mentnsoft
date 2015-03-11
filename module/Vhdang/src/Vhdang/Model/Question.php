<?php

namespace Vhdang\Model;

class Question{
    
    protected $_id;
    protected $_nick;
    protected $_admin; // neu admin not null thi day la tin nhan
    protected $_subject;
    protected $_content;
    protected $_datecreated;
    protected $_lastupdate;
    protected $_read;
    protected $_answer;
    
    protected $_count;


	public function exchangeArray($data){
    
        
        $this->_id = (!empty($data['id'])) ? $data['id'] : null;
        $this->_nick = (!empty($data['nick'])) ? $data['nick'] : null;
        $this->_admin = (!empty($data['admin'])) ? $data['admin'] : null;
        $this->_subject = (!empty($data['subject'])) ? $data['subject'] : null;
        $this->_content = (!empty($data['content'])) ? $data['content'] : null;
        $this->_datecreated = (!empty($data['datecreated'])) ? $data['datecreated'] : null;
        $this->_lastupdate = (!empty($data['lastupdate'])) ? $data['lastupdate'] : null;
        $this->_read = (!empty($data['read'])) ? $data['read'] : null;
        $this->_answer = (!empty($data['answer'])) ? $data['answer'] : null;
        $this->_count = (!empty($data['count'])) ? $data['count'] : null;
    
    }
    

    /**
     * @return the $_admin
     */
    public function getAdmin() {
    	return $this->_admin;
    }
    
    /**
     * @param field_type $_admin
     */
    public function setAdmin($_admin) {
    	$this->_admin = $_admin;
    }
    

    /**
     * @return the $_count
     */
    public function getCount() {
    	return $this->_count;
    }
    
    /**
     * @param field_type $_count
     */
    public function setCount($_count) {
    	$this->_count = $_count;
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
	 * @return the $_subject
	 */
	public function getSubject() {
		return $this->_subject;
	}

	/**
	 * @return the $_content
	 */
	public function getContent() {
		return $this->_content;
	}

	/**
	 * @return the $_datecreated
	 */
	public function getDatecreated() {
		return $this->_datecreated;
	}

	/**
	 * @return the $_lastupdate
	 */
	public function getLastupdate() {
		return $this->_lastupdate;
	}

	/**
	 * @return the $_read
	 */
	public function getRead() {
		return $this->_read;
	}

	/**
	 * @return the $_answer
	 */
	public function getAnswer() {
		return $this->_answer;
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
	 * @param field_type $_subject
	 */
	public function setSubject($_subject) {
		$this->_subject = $_subject;
	}

	/**
	 * @param field_type $_content
	 */
	public function setContent($_content) {
		$this->_content = $_content;
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

	/**
	 * @param field_type $_lastupdate
	 */
	public function setLastupdate($_lastupdate) {	    
	    $date = \DateTime::createFromFormat("d-m-Y", $_lastupdate);
	    if ($date){
	    	$this->_lastupdate =  $date->format('Y-m-d');
	    } else{
	    	$this->_lastupdate = $_lastupdate;
	    }
	}

	/**
	 * @param field_type $_read
	 */
	public function setRead($_read) {
		$this->_read = $_read;
	}

	/**
	 * @param field_type $_answer
	 */
	public function setAnswer($_answer) {
		$this->_answer = $_answer;
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