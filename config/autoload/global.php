<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db'    =>  array(
    		'driver'    => 'Pdo',
    		'dsn'      => 'mysql:dbname=mentnsoft;host=localhost',
    		'driver_options' => array(
    				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
    		),
    
    ),
    
    'cache' => array(
    		'adapter' => array(
    				'name' => 'filesystem',
    		         
    		),
    		'options' => array(
    				'cache_dir' => 'data/cache/',
        		    'dirLevel' => 2,
        		    'cacheDir' => 'data/cache',
        		    'dirPermission' => '0755',
        		    'filePermission' => '0666',
        		    'namespaceSeparator' => '-db-',
        		    'namespace'   => 'saonam',
        		    'writable'    => true
    				// other options
    		),
            'plugins' => array(
            		'serializer',
            		//throw exceptions on cache errors
            		'exception_handler' => array(
            				'throw_exceptions' => true
            		),
            ),
    ),
    
    /* 'Zend\Cache\StorageFactory' => function() {
     return \Zend\Cache\StorageFactory::factory(
     		array(
     				'adapter' => array(
     						'name' => 'filesystem',
     						'options' => array(
     								'dirLevel' => 2,
     								'cacheDir' => 'data/cache',
     								'dirPermission' => 0755,
     								'filePermission' => 0666,
     								'namespaceSeparator' => '-db-'
     						),
     				),
     				'plugins' => array(
     						'serializer',
     						//throw exceptions on cache errors
     						'exception_handler' => array(
     								'throw_exceptions' => true
     						),
     				),
     		)
     );
    },
    
    'aliases' => array(
    		'cache' => 'Zend\Cache\StorageFactory',
    ), */
    
    'service_manager'   => array(
    		'factories'   => array(
    				'Zend\Db\Adapter\Adapter'   => 'Zend\Db\Adapter\AdapterServiceFactory',    		   
    		),
    ),
    
    'module_layouts' => array(
    		'Saonam' => 'layout/layout.phtml',
    		'Vhdang' => 'layout/vhdang_layout.phtml',
    ),
    
    
);
