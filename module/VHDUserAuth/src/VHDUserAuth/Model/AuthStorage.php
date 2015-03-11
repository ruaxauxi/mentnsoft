<?php

namespace VHDUserAuth\Model;

use Zend\Authentication\Storage;
 
/**
 * This class is used to store user sessions
 * @author haidangvo
 * @copyright 06/2013
 *
 */
class AuthStorage extends Storage\Session{
	
    public function setRememberMe($rememberMe = 0, $time = 2592000){
    	if ($rememberMe == 1) {
    	    
    		$this->session->getManager()->rememberMe($time);
    		 
    		return $this->session;
    	}
    }
    
    
    public function forgetMe(){
    	$this->session->getManager()->forgetMe();
    }
    
    public function getSessionId(){
        return $this->session->getManager()->getId();
    }
    
    public function getUserData(){
        return (array)$this->read();
    }
    
    public function isAdmin(){
        $data = (array) $this->read();
        if (isset($data['isroot'])){
            return true;
        }else{
            return false;
        }
    }
    public function isSuperUser(){
        $data = (array) $this->read();
        if (isset($data['isroot']) && $data['isroot'] == 1){
        	return true;
        }else{
        	return false;
        }
    }
    
    public function getUserGroup(){
        $data = (array) $this->read();
        if (isset($data['group']) ){
        	return 'group'.$data['group'] ;
        }else{
        	return 'guest';// base group
        }
    }
    
   /*  public function getUserType(){
        if ($this->isAdmin()){
            return 'admin';
        }
        if ($this->isSuperUser()){
            return 'superuser';
        }
        if ($this->getNick()){
            return 'customer';
        }
        
        return 'guest';
    }
     */
    public function isLogin(){
        if ($this->getNick()){
            return true;
        }else{
            return false;
        }
    }
    
    public function getNick(){
        $data = (array) $this->read();
        if (isset($data['nick']) ){
        	return $data['nick'] ;
        }else{
        	return null;
        }        
    }
    
    
    public function saveData($data){
        $this->write($data);
    }
    
    
     
}