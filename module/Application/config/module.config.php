<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
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
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
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
            'Application\Controller\Index' => 'Application\Controller\IndexController'
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
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'asset_manager' => array(
        'resolver_configs'  => array(
            'collections' => array(
                '_/asset/js/vendor.js' => array(
                    '/jquery/jquery.min.js',
                    '/twbs/bootstrap/dist/js/bootstrap.min.js',
                ),
                '_/asset/js/vendor.ie.js' => array(
                    '/respond/dest/respond.min.js',
                    '/html5shiv/dist/html5shiv.js',
                ),
            ),
            'paths' => array(
               __DIR__ . '/../../../public/_/components',
               __DIR__ . '/../../../vendor',
            ),
            'map'   => array(
//                 '_/asset/js/jquery.min.map' => __DIR__ . '/../../../vendor/components/jquery/jquery.min.map', //TODO check if it's required
                '_/asset/css/style.css' => __DIR__ . '/../asset/less/style.less',
                '_/asset/fonts/fontawesome-webfont.eot'  => __DIR__ . '/../../../vendor/fortawesome/font-awesome/fonts/fontawesome-webfont.eot',
                '_/asset/fonts/fontawesome-webfont.svg'  => __DIR__ . '/../../../vendor/fortawesome/font-awesome/fonts/fontawesome-webfont.svg',
                '_/asset/fonts/fontawesome-webfont.ttf'  => __DIR__ . '/../../../vendor/fortawesome/font-awesome/fonts/fontawesome-webfont.ttf',
                '_/asset/fonts/fontawesome-webfont.woff'  => __DIR__ . '/../../../vendor/fortawesome/font-awesome/fonts/fontawesome-webfont.woff',
                '_/asset/fonts/FontAwesome.otf'  => __DIR__ . '/../../../vendor/fortawesome/font-awesome/fonts/FontAwesome.otf',

            )
        ),
        'filters'   => array(
            '_/asset/css/style.css' => array(
                array('filter' => 'LessphpFilter')
            )
        )
    ),

    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
