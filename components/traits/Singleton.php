<?php
namespace components\traits;

/**
 * Provides singleton mechanism.
 * Created by PhpStorm.
 * User: max
 * Date: 3/1/17
 * Time: 2:28 PM
 */
trait Singleton {
	/**
	 * @var static $_oInstance self class instance
	 */
	protected static $_oInstance;



	/**
	 * Checks whether this class was already initialised.
	 * @param boolean $bRefresh whether to refresh the object if it exists
	 * @return static self class instance
	 */
	final public static function instance($bRefresh = false) {
		if ($bRefresh) {
			return static::$_oInstance = new static;
		} else {
			return isset(static::$_oInstance) ? static::$_oInstance : static::$_oInstance = new static;
		}
	}



	/**
	 * Refreshes the instance.
	 * @return static self class instance
	 */
	final public static function clean() {
		return static::$_oInstance = new static;
	}



	/**
	 * Handle dynamic, static calls to the object.
	 * @param string $sMethod method name
	 * @param null|array $aArguments array of method arguments
	 * @return mixed
	 */
	public static function __callStatic($sMethod, $aArguments = null) {
		// get instance
		$oInstance = static::instance();

		// run the method
		$iArguments = is_array($aArguments) ? count($aArguments) : 0;
		switch ($iArguments) {
			case 0:
				return $oInstance->$sMethod();
			case 1:
				return $oInstance->$sMethod($aArguments[0]);
			case 2:
				return $oInstance->$sMethod($aArguments[0], $aArguments[1]);
			case 3:
				return $oInstance->$sMethod($aArguments[0], $aArguments[1], $aArguments[2]);
			case 4:
				return $oInstance->$sMethod($aArguments[0], $aArguments[1], $aArguments[2], $aArguments[3]);
			default:
				return call_user_func_array(array($oInstance, $sMethod), $aArguments);
		}
	}


	/**
	 * Restricts the '__wakeup' magic method.
	 */
	final public function __wakeup() {}



	/**
	 * Restricts the '__clone' magic method.
	 */
	final public function __clone() {}
}