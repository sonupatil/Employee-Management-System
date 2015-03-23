<?php
 return array(
     'controllers' => array(
         'invokables' => array(
            'EmployeeManagementSystem\Controller\Admin' 	=> 'EmployeeManagementSystem\Controller\AdminController',
         	'EmployeeManagementSystem\Controller\Employee' 	=> 'EmployeeManagementSystem\Controller\EmployeeController',
         	'EmployeeManagementSystem\Controller\Login' 	=> 'EmployeeManagementSystem\Controller\LoginController',
         		
         ),
     ),

     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
         	'login' => array(
         			'type'    => 'segment',
         			'options' => array(
         					'route'    => '/login[/:action][/:id]',
         					'constraints' => array(
         							'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
         							'id'     => '[0-9]+',
         					),
         					'defaults' => array(
         							'controller' => 'EmployeeManagementSystem\Controller\Login',
         							'action'     => 'login',
         					),
         			),
         	),
         		
         		
             'admin' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/admin[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'EmployeeManagementSystem\Controller\Admin',
                         'action'     => 'index',
                     ),
                 ),
             ),
        
     		'employee' => array(
     			'type'    => 'segment',
     			'options' => array(
    				'route'    => '/employee[/:action][/:id]',
     				'constraints' => array(
     					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
 						'id'     => '[0-9]+',
     				),
    				'defaults' => array(
     					'controller' => 'EmployeeManagementSystem\Controller\Employee',
     					'action'     => 'index',
     				),
     			),
     		),
     	),
     ),

 	'home' => array(
 			'type' => 'Zend\Mvc\Router\Http\Literal',
 			'options' => array(
 					'route'    => '/',
 					'defaults' => array(
 							'controller' => 'EmployeeManagementSystem\Controller\Login',
 							'action'     => 'index',
 					),
 				),
 	),
     'view_manager' => array(
         'template_path_stack' => array(
             'employee-management-system' => __DIR__ . '/../view',
         ),
     ),
 );