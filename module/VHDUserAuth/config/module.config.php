<?php
return array(
    
    // config for Acl
    'acl_config'    => array(
    		'configuration' => include __DIR__ . '/acl.config.php'
    ),
    
    /* 'controllers' => array(
        'invokables' => array(
            'VHDUserAuth\Controller\VHDUserAuth' => 'VHDUserAuth\Controller\VHDUserAuthController',
        ),
    ), */
/*     'router' => array(
        'routes' => array(
            'v-h-d-user-auth' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/vHDUserAuth',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'VHDUserAuth\Controller',
                        'controller'    => 'VHDUserAuth',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
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
            ),
        ),
    ), */
    /* 'view_manager' => array(
        'template_path_stack' => array(
            'VHDUserAuth' => __DIR__ . '/../view',
        ),
    ), */
);
