<?php

namespace VHDUserAuth\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;


/**
 * @category Vhdang
 * @author haidangvo
 * @version 1.0
 * @copyright 6/2013
 *
 */
class Acl extends ZendAcl {

    const DEFAULT_ROLE = 'guest';
    
    public function __construct($config){
        if (!isset($config['acl']['roles']) || !isset($config['acl']['resources'])){
            throw new \Exception('ACL config not found!');
        }
        
       
        
        $roles = $config['acl']['roles'];
        if(!isset($roles[self::DEFAULT_ROLE])){
            $roles[self::DEFAULT_ROLE] = '';
        }
        $resources = $config['acl']['resources'];
       
        $this   ->_addRoles($roles)
                ->_addResources($resources);
        
    }
    
    protected function _addRoles($roles){
        foreach($roles as $role => $parent){
            $newRole = new Role($role);
            if (!$this->hasRole($newRole)){
                if (empty($parent)){
                    $parent = array();
                }else{
                    $parent = explode(',', $parent);
                }
                
                $this->addRole($newRole,$parent);
            }
        }
        
        return $this;
    }
    
    protected function _addResources($resources){
        foreach($resources as $permission => $controllers){
            foreach($controllers as $controller => $actions){
                $newResource = new Resource($controller);
                if ($controller == 'all'){
                    $controller = null;
                }else{
                    if(!$this->hasResource($newResource)){
                        $this->addResource($newResource);
                    }
                }
                
                foreach($actions as $action => $role){
                    $action = $action == 'all'? null:$action;
                   
                    if ($permission == 'allow'){
                        if (gettype($role) == 'array'){
                           foreach($role as $r){
                               $this->allow($r,$controller,$action);
                           } 
                        }else{
                            $this->allow($role,$controller,$action);
                        }                        
                       
                    }elseif($permission == 'deny'){
                        if (gettype($role) == 'array'){
                            foreach($role as $r){
                                $this->deny($role,$controller,$action);
                            }                            
                        }else{
                            $this->deny($role,$controller,$action);
                        }                        
                    }else{
                        throw new \Exception('No valid permission defined: ' . $permission);                        
                    }
                }
            }
        }
        
        return $this;
    }
    
}