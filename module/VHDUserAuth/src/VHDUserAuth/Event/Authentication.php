<?php

namespace  VHDUserAuth\Event;

use Zend\Mvc\MvcEvent as MvcEvent;
use VHDUserAuth\Controller\Plugin\UserAuthentication as AuthPlugin;
use VHDUserAuth\Acl\Acl as AclClass;

/**
 * Authentication Event handler class
 * This event handles authenticaion
 * 
 * @category user
 * @package user event
 * @author haidangvo
 * @copyright 06/2013
 *
 */
class Authentication {
	
     /**
      * @var AuthPlugin
      */
     protected $_userAuth = null;
     
     /**
      * @var AclClass
      */
     protected $_aclClass = null;
     
     public function preDispatch(MvcEvent $event){
        
         $userAuth = $this->getUserAuthPlugin();
         $acl = $this->getAclClass();
         $role = AclClass::DEFAULT_ROLE;
         
         $role = $this->_userAuth->getUserGroup();
         
         $routeMatch = $event->getRouteMatch();
         $controller = $routeMatch->getParam('controller');
         $action    = $routeMatch->getParam('action');
         
         if (!$acl->hasResource($controller)){
             throw new \Exception('Resource '. $controller . ' not defined.');
         }
         if (!$acl->isAllowed($role,$controller,$action)){
             
             $target = $event->getTarget ();            
             // Do what ever you want to check the user's identity
             $url = $event->getRouter ()->assemble (array (
             		"controller" => "syserror",
                  
             ), array (
             		'name' => 'syserror',
                  
             ) );
             
             $action = "/deny";
             
             $url .= $action;
             
             $response = $event->getResponse ();
             $response->setHeaders ( $response->getHeaders ()->addHeaderLine ( 'Location', $url ));
             $response->setStatusCode ( 302 );
             $response->sendHeaders ();
             exit ();
          }
             
          if (!$userAuth->isLogin() && $controller !="user" && $action !="login"){
              $url = $event->getRouter ()->assemble (array (
              		"controller" => "user",
                     'action'    => 'login'              
              ), array (
              		'name' => 'user',
              ) );              
            
              $response = $event->getResponse ();
              $response->setHeaders ( $response->getHeaders ()->addHeaderLine ( 'Location', $url ));
              $response->setStatusCode ( 302 );
              $response->sendHeaders ();
              exit ();
              
          }   
     }
      
     
     
     /**
      * Gets Authenticaion Plugin
      * @return \Vhdang\Controller\Plugin\UserAuthentication
      */
     public function getUserAuthPlugin(){
         if ($this->_userAuth === null ){
             $this->_userAuth = new AuthPlugin();
         }
         
         return $this->_userAuth;
     }
     
     
     /**
      * Sets Authentication Plugin
      * @param AuthPlugin $authlpugin
      * @return \Vhdang\Event\Authentication
      */
     public function setUserAuthPlugin(AuthPlugin $authlpugin){
         $this->_userAuth = $authlpugin;
         return $this;
     }
     
     /**
      * Sets Acl class
      * @param AclClass $acl
      * @return \Vhdang\Event\Authentication
      */
     public function setAclClass(AclClass $acl){
         $this->_aclClass = $acl;
         return $this;
     }
     
     /**
      * Get Acl Class
      * @return \Vhdang\Acl\Acl
      */
     public function getAclClass(){
         if($this->_aclClass === null){
             $this->setAclClass(new AclClass());
         }
         return $this->_aclClass;
     }
}