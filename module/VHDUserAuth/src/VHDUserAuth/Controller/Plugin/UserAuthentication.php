<?php

namespace VHDUserAuth\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Db\Adapter\Adapter as ZendDbAdapter;

use VHDUserAuth\Model\AuthStorage; 
use Vhdang\Model\CustomerTable;
use Vhdang\Model\AdminTable;

/**
 * file for User Authentication class
 * 
 * @category user
 * @package vhdang
 * @author haidangvo
 * @copyright 06/2013
 *
 */
class UserAuthentication extends AbstractPlugin {
    
    /**
     * @var DbTableAuthAdapter
     */
    protected $_dbTableAuthAdapter = null;
    
    
    /**
     * Zend db adapter
     * @var Zend\Db\Adapter\Adapter
     */
    protected $_zendDbAdapter = null;
    
    /**
     * @var AuthenticationService
     */
    protected $_authService = null;
    
    
    /**
     * Table for identiyfing user
     * @var string
     */
    protected $_identityTable = 'customer';
    protected $_alIdentityTable = 'admin';
    
    /**
     * column for identifying user
     * @var string
     */
    protected $_identityColumn = 'nick';
    
    /**
     * column for identifying user
     * @var string
     */
    protected $_credentialColumn = 'password';
    
    
    
    /**
     * session storage 
     * @var \Vhdang\Model\AuthStorage
     */
    protected $_storage;
    
    /**
     * get Zend\Db\Adapter\Adapter
     * @throws \Exception
     * @return \Vhdang\Controller\Plugin\Zend\Db\Adapter\Adapter
     */
    public function getZendDbAdapter(){
        if ($this->_zendDbAdapter === null){
            throw new \Exception('Could not connect to database: Authentication plugin');
            
        }
        
        return $this->_zendDbAdapter;
    }
    
    public function setZendDbAdapter(ZendDbAdapter $zendDbAdapter){
        $this->_zendDbAdapter = $zendDbAdapter;
        return $this;
    }
    
    /**
     * Sets Authentication Service
     * @param AuthenticationService $authService
     * @return \Vhdang\Controller\Plugin\UserAuthentication
     */
    public function setAuthService(AuthenticationService $authService){
        $this->_authService = $authService;
        return $this;
    }
    
    /**
     * Gets Authentication service
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthService(){
        if ($this->_authService === null){
            $authService = new AuthenticationService();
            $authService->setAdapter($this->getDbTableAuthAdapter());
            $authService->setStorage($this->getStorage());
            $this->setAuthService($authService);
            
        }
        return $this->_authService;
    }
    
    /*  
     * Sets Authentication Db Adapter
     * @param AuthAdapter $authadapter
     * @return \Vhdang\Controller\Plugin\UserAuthentication
     */
    public function setDbTableAuthAdapter(DbTableAuthAdapter $dbTableAuthAdapter){
        $this->_dbTableAuthAdapter = $dbTableAuthAdapter;
        return $this;
    }
      
    /**
     * Gets Authentication Db Adapter
     * @return \Zend\Authentication\Adapter\DbTable
     */
    public function getDbTableAuthAdapter(){
        if ($this->_dbTableAuthAdapter === null){
            
            $ZendDbApdater = $this->getZendDbAdapter();
            
           
            $this->setDbTableAuthAdapter(new DbTableAuthAdapter($ZendDbApdater,
                                                    $this->_identityTable,
                                                    $this->_identityColumn,
                                                    $this->_credentialColumn,
                                                    ''
                                        ));
            
        }
        return $this->_dbTableAuthAdapter;
    }
    
    
    /**
     * check if user is logged in
     * @return bool
     */
    public function isLogin(){
    	return $this->getAuthService()->hasIdentity();
    }
    
    public function isAdmin(){
        return $this->getStorage()->isAdmin();
    }
    
    public function getNick(){
        return $this->getStorage()->getNick();
    }
    
    public function getUserGroup(){
        return $this->getStorage()->getUserGroup();
    }
    
    /**
     * gets current Identity
     * @return Ambigous <\Zend\Authentication\mixed, NULL, \Zend\Authentication\Storage\mixed>
     */
    public function getIdentity(){
        return $this->getAuthService()->getIdentity();
    }
    
    /**
     * @param AuthStorage $storage
     * @return \Vhdang\Controller\Plugin\UserAuthentication
     */
    public function setStorage(AuthStorage $storage){
    	$this->_storage = $storage;
    	return $this;
    }
    
    public function getStorage(){
    	return $this->_storage;
    }
    
    public function getUserData(){
        return $this->getStorage()->getUserData();
    }
     
    public function clearData(){
    	$this->getStorage()->forgetMe();
    	$this->getAuthService()->clearIdentity();
    }
    
    /**
     * Authenticate User
     * @param string $nick
     * @param string $password
     * @param number $remember
     * @return boolean
     */
    public function authenticate($nick,$password,$remember = 0){
    	$this->getDbTableAuthAdapter()
    	->setIdentity($nick)
    	->setCredential($password);
    	 
    	//$this->getDbTableAuthAdapter()->getDbSelect()->where('active = 1');
    	
    	$result = $this->getDbTableAuthAdapter()->authenticate();
    	$isAdmin  = false;
    	if (!$result->isValid()) {
    	    $ZendDbApdater = $this->getZendDbAdapter();
    	    $this->setDbTableAuthAdapter(new DbTableAuthAdapter($ZendDbApdater,
    	    		$this->_alIdentityTable,
    	    		$this->_identityColumn,
    	    		$this->_credentialColumn,
    	    		''
    	    ));
    	    
    	    $this->getDbTableAuthAdapter()
    	    ->setIdentity($nick)
    	    ->setCredential($password);
    	    $result = $this->getDbTableAuthAdapter()->authenticate();    	    
    	    $isAdmin = true;
    	    if (!$result->isValid()) {
    	        return false;
    	    }
    	}
    	
    	if ( $remember == 1 ) {
    		$this->getStorage()->setRememberMe(1);    		 
    		$this->setStorage($this->getStorage());
    	}
    	 
    	if ($isAdmin){
    	    $usertable = new AdminTable($this->getZendDbAdapter());
    	    
    	}else{
    	    $usertable = new CustomerTable($this->getZendDbAdapter());
    	    
    	}
    	$userdata =  $usertable->getUserLoginData($nick);
    	$this->getStorage()->saveData($userdata);
    	
    	return true;
    	
    }
    
	
}