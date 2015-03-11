<?php

namespace Vhdang\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;

class VhdangNavigation extends DefaultNavigationFactory {
	protected function getPages(ServiceLocatorInterface $serviceLocator) {
	    
	    $authPlugin = $serviceLocator->get('UserAuthentication');
	    
		$config ['navigation'] [$this->getName ()] = array ();
		
		
		
		if ($authPlugin->isAdmin()){
		    $config ['navigation'] [$this->getName ()] ['user'] = array (
		    		'label' => 'Quản lý người dùng',
		    		'route' => 'home',
		    		'pages' => array (
		    				array (
		    						'label' => 'Khách hàng',
		    						'route' => 'user',
		    						'params' => array (
		    								'action' => 'customer'
		    						)
		    				),
		    				array (
		    						'label' => 'Admin',
		    						'route' => 'user' ,
		    						'params' => array (
		    								'action' => 'admin'
		    						)
		    				)
		    		)
		    );
		    
		    $config ['navigation'] [$this->getName ()] ['account_manament'] = array(
		        'label' => 'Tài khoản',
		        'route' => 'home',
		        'pages' => array (
		        		array (
		        				'label' => 'Khách hàng',
		        				'route' => 'user',
		        				'params' => array (
		        						'action' => 'customer'
		        				)
		        		),
		        		array (
		        				'label' => 'Admin',
		        				'route' => 'user' ,
		        				'params' => array (
		        						'action' => 'admin'
		        				)
		        		)
		        )
		    );
		    
		}
		
		if ($authPlugin->isLogin()){
		    
		    
		    
		    
			$config ['navigation'] [$this->getName ()] ['account'] = array(
					'label' => $authPlugin->getNick(),
					'route' => 'home',
					'pages' => array (
    					    array (
    					    		'label' => 'Cập nhật địa chỉ',
    					    		'route' => 'user',
    					    		'params' => array (
    					    				'action' => 'address'
    					    		)
    					    ),
							array (
									'label' => 'Thoát',
									'route' => 'user',
									'params' => array (
											'action' => 'logout'
									)
							),
					)
			);
		}
		
		/*  $config ['navigation'] [$this->getName ()] ['home'] ['pages'] ['user'] = array (
				'label' => 'Tài khoản',
				'route' => 'user',
				'pages' => array (
						array (
								'label' => 'Khách hàng',
								'route' => 'user',
								'params' => array (
										'action' => 'customer' 
								)
								 
						),
						array (
								'label' => 'Admin',
								'route' => 'user',
								'params' => array (
										'action' => 'admin' 
								) 
						) 
				) 
		);  */ 
		
		$mvcEvent = $serviceLocator->get ( 'Application' )->getMvcEvent ();
		
		$routeMatch = $mvcEvent->getRouteMatch ();
		$router = $mvcEvent->getRouter ();
		$pages = $this->getPagesFromConfig ( $config ['navigation'] [$this->getName ()] );
		
		$this->pages = $this->injectComponents ( $pages, $routeMatch, $router );
		
		return $this->pages;
	}
	
	/*
	 * protected function urlString($string) { $urlString = strtolower($string); $urlString = preg_replace('/[^a-z0-9]+/i', '-', $urlString); $urlString = preg_replace('/-$/', '', $urlString); return $urlString; }
	 */
}