<?php
namespace components;

/**
 * Stores config data.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 14:13
 */
class Config {
	// define DB credentials
	const DB = [
		'host' => 'localhost',
		'port' => null,
		'dbname' => 'test',
		'user' => 'user',
		'password' => 'password',
		'prefix' => 'test_'
	];

	// encoding
	const ENCODING = 'utf-8';
}