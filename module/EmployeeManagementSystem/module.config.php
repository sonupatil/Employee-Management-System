<?php
return array(
		'controllers' => array(
				'invokables' => array(
						'EmployeeManagementSystem\Controller\Admin' => 'EmployeeManagementSystem\Controller\AdminController',
				),
		),
		'view_manager' => array(
				'template_path_stack' => array(
						'album' => __DIR__ . '/../view',
				),
		),
);