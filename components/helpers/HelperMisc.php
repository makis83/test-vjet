<?php
namespace components\helpers;

use components\Config;

/**
 * Miscelanous helper methods.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 20:54
 */
class HelperMisc {
	/**
	 * Fixes int variable type.
	 * @return null|mixed converted value
	 * @param string $sValue value
	 */
	public static function fixInt($sValue) {
		// validate
		if (is_null($sValue) or !mb_strlen($sValue, Config::ENCODING)) {return null;}

		// check whether the variable is integer
		$sPattern = '/^([0-9]+)$/';
		if (preg_match($sPattern, $sValue)) {
			return (int) $sValue;
		}

		// check whether the variable is float
		$sPattern = '/^([0-9]+(?:\.[0-9]+)?)$/';
		if (preg_match($sPattern, $sValue)) {
			return (float) $sValue;
		}

		// otherwise return the original value
		return $sValue;
	}



	/**
	 * Safe data receiving via GET or POST methods.
	 * @return null|mixed processed data or null
	 * @param array|string $mData original array or string item
	 * @param boolean $bStripTags whether to strip tags
	 * @uses HelperText::csvFix
	 */
	public static function receive($mData, $bStripTags = true) {
		// validate
		if (!is_int($mData) and !is_float($mData) and !is_string($mData) and !(is_array($mData) and !empty($mData))) {return null;}

		// check whether the transferred data is array
		if (is_array($mData)) {
			return array_map(function ($mData) use ($bStripTags) {
				return self::receive($mData, $bStripTags);
			}, $mData);
		} else {
			$sData = $mData;

			// validate var length
			if (!mb_strlen($sData, Config::ENCODING)) {return null;}

			// strip tags
			if ($bStripTags) {
				$sData = strip_tags($mData);
			}

			// strip slashes
			if (1 === ini_get("magic_quotes_gpc")) {
				$sData = stripslashes($sData);
			}

			// result
			return self::fixInt($sData);
		}
	}



	/**
	 * Detects whether user browser is IE.
	 * @return boolean true or false
	 */
	public static function isIE() {
		$sPattern = '/(?i)msie [0-9]+/iUu';
		return preg_match($sPattern, $_SERVER['HTTP_USER_AGENT']) ? true : false;
	}



	/**
	 * Indexes the array by the specified key.
	 * @return null|array null or indexed array
	 * @param array $aData original array
	 * @param string $sKeyIndex key name for index
	 * @param null|array|string $mKeyValue key name for value or array of key names (set null if the value must be the full original array)
	 * @param null|boolean $bPreserveKey whether to preserve indexed key name in value array
	 */
	public static function indexBy($aData, $sKeyIndex, $mKeyValue = null, $bPreserveKey = true) {
		// validate
		if (!(is_array($aData) and !empty($aData)) or is_null($sKeyIndex)) {return null;}

		// loop through array
		$aResult = null;
		foreach ($aData as $mKey => $mTmp) {
			// deal with value
			if (is_array($mTmp) and !empty($mTmp)) {
				// detect if the key exists
				if (is_null($mTmp[$sKeyIndex])) {continue;}

				// index
				$mIndex = $mTmp[$sKeyIndex];
				if ((is_string($mIndex) and ctype_digit($mIndex))) {
					$mIndex = (int) $mIndex;
				}

				// value
				if (!is_null($mKeyValue)) {
					// get array of key values
					if (is_array($mKeyValue)) {
						// loop through array
						foreach ($mKeyValue as $sKeyValue) {
							$aResult[$mIndex][$sKeyValue] = array_key_exists($sKeyValue, $mTmp) ? $mTmp[$sKeyValue] : null;
						}
					} else {
						$aResult[$mIndex] = array_key_exists($mKeyValue, $mTmp) ? $mTmp[$mKeyValue] : null;
					}
				} else {
					$aResult[$mIndex] = $mTmp;

					// detect whether to preserve index item in array
					if (!$bPreserveKey) {
						unset($aResult[$mIndex][$sKeyIndex]);
					}
				}
			}
		}

		// result
		return $aResult;
	}
}