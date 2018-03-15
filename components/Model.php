<?php
namespace components;

/**
 * Works with database tables.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 16:25
 */
abstract class Model {
	/**
	 * @var null|\PDO $_oConnection PDO connection
	 */
	protected static $_oConnection = null;



	/**
	 * Connects to DB.
	 * @return void nothing
	 */
	public function __construct() {
		// try to connect
		try {
			self::$_oConnection = new \PDO('mysql:host=' . Config::DB['host'] . ';dbname=' . Config::DB['dbname'] . (is_null(Config::DB['port']) ? null : ';port=' . Config::DB['port']) . ';charset=UTF8', Config::DB['user'], Config::DB['password'], [\PDO::ATTR_PERSISTENT => true]);
		} catch (\PDOException $oException) {
			echo $oException->getMessage();
			die();
		}
	}



	/**
	 * Gets table name.
	 * @return string table name
	 */
	abstract public static function tableName(): string;
}