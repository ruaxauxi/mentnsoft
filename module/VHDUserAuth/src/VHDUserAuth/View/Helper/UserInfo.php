<?php

namespace VHDUserAuth\View\Helper;

use Zend\View\Helper\AbstractHelper;

use VHDUserAuth\Model\AuthStorage;

class UserInfo  extends AbstractHelper{
	
    protected $storage;
    
    /**
	 * @return the $storage
	 */
	public function getStorage() {
		return $this->storage;
	}

	/**
	 * @param field_type $storage
	 */
	public function setStorage(AuthStorage $storage) {
		$this->storage = $storage;
	}
	 
	
	public function getNick(){
	    return $this->getStorage()->getNick();
	}
	
	public function getUserGroup(){
	    return $this->getStorage()->getUserGroup();
	}
	
	public function getUserData(){
	    return $this->getStorage()->getUserData();
	}
	 
	public function isLogin(){
	    return $this->getStorage()->isLogin();
	}
	
	public function isAdmin(){
	    return $this->getStorage()->isAdmin();
	}
	
	
}
