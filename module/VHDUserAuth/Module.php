<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/VHDUserAuth for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace VHDUserAuth;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements AutoloaderProviderInterface
{
    public function getServiceConfig()
    {
    	return array(
    			// sets dbadapter for all classes that implement AdapterAwareInterface
    			'initializers' => array(
    					function ($instance, $sm) {
    						if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
    							$instance->setDbAdapter($sm->get('ZendDbAdapter'));
    						}
    					}
    			),
    			'factories' => array(
    					 
    					'VHDUserAuth\Model\AuthStorage' => function($sm){
    							
    						$config = new \Zend\Session\Config\SessionConfig();
    						$config->setOptions(array(
    								'savePath' => getcwd().'/data/sessions',
    						));
    						$manager = new \Zend\Session\SessionManager($config);
    						// $manager->start(false);
    						return new \VHDUserAuth\Model\AuthStorage('userauth',null,$manager);
    					},
    					
    					'Acl'  => function($sm){
    						$config = $sm->get('config');
    						$config = $config['acl_config']['configuration'];
    						return new \VHDUserAuth\Acl\Acl($config);
    					},
    					
    					'VHDUserAuth\Event\Authentication'  => function($sm){
    						$storage = $sm->get('VHDUserAuth\Model\AuthStorage');
    						$dbAdapter = $sm->get('ZendDbAdapter');
    					
    						
    						$authlpugin = $sm->get('UserAuthentication');
    					
    						$acl = $sm->get("Acl");
    					
    						$auth = new \VHDUserAuth\Event\Authentication();
    					
    						$auth->setUserAuthPlugin($authlpugin);
    						$auth->setAclClass($acl);
    						return $auth;
    					},
    					'UserAuthentication'   => function($sm){
    					    $storage = $sm->get('VHDUserAuth\Model\AuthStorage');
    					    $dbAdapter = $sm->get('ZendDbAdapter');
    					    $authlpugin = new \VHDUserAuth\Controller\Plugin\UserAuthentication();
    					    $authlpugin->setStorage($storage);
    					    $authlpugin->setZendDbAdapter($dbAdapter);
    					    
    					    return $authlpugin;
    					}
     
    
    
    			),
    	);
    }
    
    public function init(\Zend\ModuleManager\ModuleManager $moduleManager) {
    	$sharedManager = $moduleManager->getEventManager()->getSharedManager();
    	$sharedManager->attach('Zend\Mvc\Application',
    			'dispatch',
    			array($this, 'mvcPreDispatch'), 100);
    
    }
    
    public function mvcPreDispatch($event) {
    
    	$sm = $event->getTarget()->getServiceManager();
    	$auth = $sm->get('VHDUserAuth\Event\Authentication');
    	return $auth->preDispatch($event);
    }
    
    public function getControllerPluginConfig()
    {
    	return array(
    			'factories' => array(
    
    					'UserAuthPlugin' => function ($sm) {
    						$serviceLocator = $sm->getServiceLocator();
    						$storage = $serviceLocator->get('VHDUserAuth\Model\AuthStorage');
    						$dbAdapter = $serviceLocator->get('ZendDbAdapter');
    						$controllerPlugin = new \VHDUserAuth\Controller\Plugin\UserAuthentication();
    						$controllerPlugin->setStorage($storage);
    						$controllerPlugin->setZendDbAdapter($dbAdapter);
    						return $controllerPlugin;
    					},
    
    			),
    	);
    }
    
    
    public function getViewHelperConfig()
    {
    	return array(
    			'factories' => array(
    					'UserInfo' => function ($sm) {
    						$locator = $sm->getServiceLocator();
    						$viewHelper = new \VHDUserAuth\View\Helper\UserInfo();
    						$storage =  $locator->get('VHDUserAuth\Model\AuthStorage');
    						$viewHelper->setStorage($storage);
    						return $viewHelper;
    					},
    
    			),
    	);
    
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}
