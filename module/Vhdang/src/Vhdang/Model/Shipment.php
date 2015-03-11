<?php

namespace Vhdang\Model;

use Vhdang\Utils\String;
class Shipment {
	
	protected $_id;
	protected $_ship_date;
	protected $_ship_name;
	protected $_weight;
	protected $_note;
	protected $_finish;
	protected $_delivered;
	protected $_checked;
	protected $_finalized;
	protected $_paid; // da thanh toan hay chua? ,mac dinh 0
	
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
	 * @return the $_finalized
	 */
	public function getFinalized() {
		return $this->_finalized;
	}

	/**
	 * @param field_type $_finalized
	 */
	public function setFinalized($_finalized) {
		$this->_finalized = $_finalized;
	}

	/**
	 * @return the $_checked
	 */
	public function getChecked() {
		return $this->_checked;
	}

	/**
	 * @param field_type $_checked
	 */
	public function setChecked($_checked) {
		$this->_checked = $_checked;
	}

	/**
	 * @return the $_delivered
	 */
	public function getDelivered() {
		return $this->_delivered;
	}

	/**
	 * @param field_type $_delivered
	 */
	public function setDelivered($_delivered) {
		$this->_delivered = $_delivered;
	}

	/**
	 * @return the $_id
	 */
	public function getId() {
		return $this->_id;
	}

	/**
	 * @return the $_ship_date
	 */
	public function getShip_date() {
		return $this->_ship_date;
	}

	/**
	 * @return the $_ship_name
	 */
	public function getShip_name() {
		return $this->_ship_name;
	}

	/**
	 * @return the $_weight
	 */
	public function getWeight() {
		
		return $this->_weight;
	}

	/**
	 * @return the $_note
	 */
	public function getNote() {
		return $this->_note;
	}

	/**
	 * @return the $_finish
	 */
	public function getFinish() {
		return $this->_finish;
	}

	/**
	 * @param field_type $_id
	 */
	public function setId($_id) {
		$this->_id = $_id;
	}

	/**
	 * @param field_type $_ship_date
	 */
	public function setShip_date($_ship_date) {
		
		$date = \DateTime::createFromFormat("d-m-Y", $_ship_date);
		if ($date){
			$this->_ship_date =  $date->format('Y-m-d');
		} else{
			$this->_ship_date = $_ship_date;
		}
		
	 
	}

	/**
	 * @param field_type $_ship_name
	 */
	public function setShip_name($_ship_name) {
		$this->_ship_name = $_ship_name;
	}

	/**
	 * @param field_type $_weight
	 */
	public function setWeight($_weight) {
		$_weight = String::formatNumber($_weight);
		$this->_weight = $_weight;
	}

	/**
	 * @param field_type $_note
	 */
	public function setNote($_note) {
		$this->_note = $_note;
	}

	/**
	 * @param field_type $_finish
	 */
	public function setFinish($_finish) {
		$this->_finish = $_finish;
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