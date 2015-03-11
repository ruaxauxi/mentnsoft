<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    
		'file_upload'	=> array(
				'config'	=> include __DIR__ . '/file.config.php',
		),
		
		'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Vhdang\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'user' => array(
            		'type'    => 'segment',
            		'options' => array(
            				'route'    => '/user[/:action][-:id][/tid-:tid][/page-:page][/row-:row][.html]',
            				'constraints' => array(
            						'action' => '(?!\btid\b)(?!\bpage\b)(?!\b\row\b)[a-zA-Z][a-zA-Z0-9_-]*',
            						'id'     => '[0-9]+',
            						'tid'     => '[0-9]+',
            						'page'    => '[0-9]+',
            						'row'     => '[0-9]+',
            				),
            				'defaults' => array(
            						'controller' => 'Vhdang\Controller\User',
            						'action'     => 'index',
            				),
            		),
            ),
            
            'admin' => array(
            		'type'    => 'segment',
            		'options' => array(
            				'route'    => '/admin[/:action][/:id][/tid/:tid][/page/:page][/row/:row][.html]',
            				'constraints' => array(
            						'action' => '(?!\btid\b)(?!\bpage\b)(?!\b\row\b)[a-zA-Z][a-zA-Z0-9_-]*',
            						'id'     => '[a-zA-Z0-9_.-]*[^.html]',
            						'tid'     => '[0-9]+',
            						'page'    => '[0-9]+',
            						'row'     => '[0-9]+',
            				),
            				'defaults' => array(
            						'controller' => 'Vhdang\Controller\Admin',
            						'action'     => 'index',
            				),
            		),
            ),
            
            'customer' => array(
            		'type'    => 'segment',
            		'options' => array(
            				'route'    => '/customer[/:action][/:id][/page/:page][/row/:row][.html]',
            				'constraints' => array(
            						'action' => '(?!\bpage\b)(?!\b\row\b)[a-zA-Z][a-zA-Z0-9_-]*',
            						'id'     => '[0-9]+',
            						'page'    => '[0-9]+',
            						'row'     => '[0-9]+',
            				),
            				'defaults' => array(
            						'controller' => 'Vhdang\Controller\Customer',
            						'action'     => 'index',
            				),
            		),
            ),
            
            'address' => array(
            		'type'    => 'segment',
            		'options' => array(
            				'route'    => '/address[/:action][/:id]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id'     => '[0-9]+',
            				),
            				'defaults' => array(
            						'controller' => 'Vhdang\Controller\Address',
            						'action'     => 'index',
            				),
            		),
            ),
            
            'upload' => array(
            		'type' => 'segment',
            		'options' => array(
            				'route'    => '/admin/upload[/:action][/:id]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[0-9]+',
            				),
            				'defaults' => array(
            						'controller' => 'Vhdang\Controller\Image',
            						'action' => 'index',
            				),
            		),
            ),
            
            'syserror' => array(
            		'type'    => 'segment',
            		'options' => array(
            				'route'    => '/syserror[/:action][/:id]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id'     => '[0-9]+',
            				),
            				'defaults' => array(
            						'controller' => 'Vhdang\Controller\Syserror',
            						'action'     => 'index',
            				),
            		),
            ),
            
            
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            
                    
/*             'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Vhdang\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ), */
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Vhdang\Controller\Index' => 'Vhdang\Controller\IndexController',
            'Vhdang\Controller\User'   => 'Vhdang\Controller\UserController',
            'Vhdang\Controller\Syserror'    => 'Vhdang\Controller\SyserrorController',
            'Vhdang\Controller\Address'     => 'Vhdang\Controller\AddressController',
            'Vhdang\Controller\Customer'    => 'Vhdang\Controller\CustomerController',
            'Vhdang\Controller\Admin'       => 'Vhdang\Controller\AdminController',
            'Vhdang\Controller\Image'	=> 'Vhdang\Controller\ImageController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/vhdang/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'paginator-slide'         => __DIR__ . '/../view/layout/slidePaginator.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
        		'ViewJsonStrategy',
        ),
    ),
);
