<?php

/**
 * This is the config file for SlmQueue. Just drop this file into your config/autoload folder (don't
 * forget to remove the .dist extension from the file), and configure it as you want
 */

return array(
	'slm_queue' => array(
			'queue_manager' => array(
					'factories' => array(
							'default' => 'SlmQueueBeanstalkd\Factory\BeanstalkdQueueFactory'
					),
			),
		
    
        'worker_strategies' => array(
            'default' => array( // per worker
            ),
            'queues' => array( // per queue
                'default' => array(
                ),
            ),
        ),

        /**
         * Allow to configure the plugin manager that manages strategies. This works like any other
         * PluginManager in Zend Framework 2.
         *
         * Add you own or override existing factories
         *
         * 'strategy_manager' => array(
         *    'factories' => array(
         *        'SlmQueue\Strategy\LogJobStrategy'               => 'MyVeryOwn\LogJobStrategyFactory',
         *    )
         * ),
         */
        'strategy_manager' => array(),

        /**
         * Allow to configure dependencies for jobs that are pulled from any queue. This works like any other
         * PluginManager in Zend Framework 2. For instance, if you want to inject something into every job using
         * a factory, just adds an element into the "factories" array, with the key being the FQCN of the job,
         * and the value the factory:
         *
         * 'job_manager' => array(
         *     'factories' => array(
         *         'Application\Job\UserJob' => 'Application\Factory\UserJobFactory'
         *     )
         * )
         *
         * Therefore, the job will be created through the factory (the identifier and content of the job will be
         * automatically set after creation). Note that this plugin manager is configured as such it automatically
         * add any unknown classes to the invokables list. This means you should only add factories and/or abstract
         * factories here.
         */
        'job_manager' => array(
        	'factories' => array(
            	'MyModule\Job\SendEmailJob' => 'MyModule\Factory\SendEmailJobFactory',
        	),
        'invokables' => array(
            'MyModule\Job\PrintHelloWorldJob' => 'MyModule\Job\PrintHelloWorldJob',
        ),
    ),


        /**
         * Allow to add queues. You need to have at least one queue. This works like any other PluginManager in
         * Zend Framework 2. For instance, if you have a queue whose name is "email", you can add it as an
         * invokable this way:
         *
         * 'queue_manager' => array(
         *     'invokables' => array(
         *         'email' => 'Application\Queue\MyQueue'
         *     )
         * )
         *
         * Please note that you can find built-in factories for several queue systems (Beanstalk, Amazon Sqs...)
         * in SlmQueueSqs and SlmQueueBeanstalk
         */
        'queue_manager' => array()
    ),
);
