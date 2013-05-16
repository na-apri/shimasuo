<?php

return [
	'config' => [
		'connection' => [
			'config' => [
				'default' => [
					'host' => '127.0.0.1',
					'port' => '6379',
					'database' => '0',
				],
			],
			'task' => 'default',
			'worker' => 'default',
		],
		'key_prefix' => 'SAMPLE_',		
	],
	'serialize' => null,
	'unserialize' =>null,
	'minChildProcess' => 4,
	'maxChildProcess' => 32,
	'threshold' => 4
];