<?php
namespace components\helpers;

use DateTime;
use components\Config;

/**
 * Helper file for manipulations with text.
 * Created by PhpStorm.
 * User: max
 * Date: 04.12.15
 * Time: 14:51
 */
class HelperText {
	/**
	 * Generates random sequence of symbols.
	 * @return string random sequence of symbols
	 * @param integer $iLength sequence length
	 * @param null|string $sCollection chars collection ('alpha', 'numeric' or 'alphanumeric')
	 * @param null|string $sCase chars case ('lower', 'higher' or 'both')
	 */
	public static function random(int $iLength = 10, $sCollection = 'alphanumeric', $sCase = 'both') {
		// get collection string
		if ('alpha' === $sCollection) {
			if ('lower' === $sCase) {
				$sABC = 'abcdefghijklmnopqrstuvwxyz';
			} else if ('higher' === $sCase) {
				$sABC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			} else {
				$sABC = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			}
		} else if ('numeric' === $sCollection) {
			$sABC = '1234567890';
		} else {
			if ('lower' === $sCase) {
				$sABC = 'abcdefghijklmnopqrstuvwxyz1234567890';
			} else if ('higher' === $sCase) {
				$sABC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			} else {
				$sABC = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
			}
		}

		// generate random sequence
		$sData = '';
		for	($i = 0; $i < $iLength; $i++) {
			$sData .= $sABC[mt_rand(0, (mb_strlen($sABC, Config::ENCODING) - 1))];
		}

		// result
		return $sData;
	}



	/**
	 * Makes intro for specified text block.
	 * @return null|string null or shortened text
	 * @param string $sText full text
	 * @param integer $iLength intro length (chars)
	 * @param boolean $bDots whether to put trailing dots
	 * @param boolean $bLine whether to use only first line of text
	 */
	public static function intro($sText = null, $iLength = 200, $bDots = false, $bLine = false) {
		// validate
		if (!mb_strlen($sText, Config::ENCODING)) {return null;}

		// remove new lines
		if ($bLine) {
			$sText = strtok($sText, "\n");
		} else {
			$sText = str_replace(array("\n", "\r", "\r\n", "\n\r"), ' ', trim(strip_tags($sText)));
		}

		// generate intro
		$sPattern = '/^(.{'.$iLength.',})\s+/iuU';
		preg_match($sPattern, $sText, $aMatch);

		// detect if intro exists
		if (isset($aMatch[1])) {
			// intro
			$sIntro = $aMatch[1];
			if ($bDots and mb_substr($sIntro, -1, null, Config::ENCODING) !== '.') {
				$sIntro .= '...';
			}

			return $sIntro;
		} else {
			return null;
		}
	}



	/**
	 * Creates the special array with status data.
	 * @return array special array with status data
	 * @param null|string $sResponse response text
	 * @param boolean $bSuccess whether the operation was successful
	 * @param mixed $mResult result (string, integer, array etc.)
	 */
	public static function status($sResponse = null, $bSuccess = false, $mResult = null) {
		return ['response' => $sResponse, 'success' => $bSuccess, 'result' => $mResult];
	}



	/**
	 * Echoes the JSON-encoded string with result.
	 * @return void nothing
	 * @param null|string|array $mResponse response text
	 * @param boolean $bSuccess whether the operation was successful
	 * @param null|string|array $mResult result (string, integer, array etc.)
	 */
	public static function json($mResponse = null, $bSuccess = false, $mResult = null) {
		// generate the response array
		$aData = ['response' => $mResponse, 'success' => $bSuccess, 'result' => $mResult];

		// send data
		echo json_encode($aData, JSON_HEX_TAG);
		exit;
	}



	/**
	 * Validates email.
	 * @return boolean true or false
	 * @param string $sEmail email
	 * @param bool $bCheckDomain whether to check domain
	 */
	public static function isValidEmail($sEmail = null, $bCheckDomain = false) {
		// validate email
		if (filter_var($sEmail, FILTER_VALIDATE_EMAIL)) {
			// check domain
			if ($bCheckDomain) {
				list($sUsername, $sDomain) = explode('@', $sEmail, 2);
				unset($sUsername);
				return checkdnsrr($sDomain, 'MX') ? true : false;
			}

			// result
			return true;
		}

		// result
		return false;
	}



	/**
	 * Validates human-readable date.
	 * @return boolean result of validation
	 * @param string $sDate date
	 */
	public static function isValidDate($sDate) {
		// pattern
		$sPattern = '/[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}/iUu';

		// validate
		if (preg_match($sPattern, $sDate)) {
			list($iDay, $iMonth, $iYear) = explode('.', $sDate);
			return checkdate($iMonth, $iDay, $iYear);
		} else {
			return false;
		}
	}



	/**
	 * Validates date or datetime date.
	 * @return boolean result of validation
	 * @param string $sDate date
	 * <code>
	 * 2018-02-06 16:09:00
	 * 2018-01-01
	 * </code>
	 * @param null|string $sFormat date format
	 */
	public static function isValidTimestamp($sDate, $sFormat = 'Y-m-d H:i:s') {
		// detect if the date is valid
		$oDate = DateTime::createFromFormat($sFormat, $sDate);
		return $oDate && $oDate->format($sFormat) == $sDate;
	}



	/**
	 * Validates hex color.
	 * @return boolean whether string is a valid hex color
	 * @param string $sColor hex color without '#' (i.e. '00FF00')
	 */
	public static function isValidHexColor($sColor) {
		if (!preg_match('/^([a-f0-9]{6}|[a-f0-9]{3})$/iUu', $sColor)) {
			return false;
		} else {
			return true;
		}
	}



	/**
	 * Detects if string or number is float.
	 * @param mixed $mNumber number to be checked
	 * @return boolean whether the number is float
	 */
	public static function isFloat($mNumber) {
		return is_float($mNumber) || is_numeric($mNumber) && ((float) $mNumber != (int) $mNumber);
	}



	/**
	 * Escapes data used for mysql update or insert, does not require mysql resource.
	 * @return boolean|string false or escaped string
	 * @param array|string $mData original string or array
	 */
	public static function mysqlEscapeMimic($mData = null) {
		// validate
		if (!mb_strlen($mData, Config::ENCODING)) {return false;}

		// check whether argument is array
		if (is_array($mData)) {
			return array_map(__METHOD__, $mData);
		}

		// check whether argument is string
		if(!empty($mData) && is_string($mData)) {
			return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $mData);
		}

		// result
		return $mData;
	}



	/**
	 * Gets proper word for using it with number of something.
	 * @return string proper word
	 * @param integer $iNumber number of items
	 * @param array $aWord array with all 3 possible words depending to the number
	 */
	public static function inflactWord($iNumber, $aWord = array('машина', 'машины', 'машин')) {
		$aCase = array (2, 0, 1, 1, 1, 2);
		return $aWord[ ($iNumber%100 > 4 && $iNumber %100 < 20) ? 2 : $aCase[min($iNumber%10, 5)] ];
	}



	/**
	 * Detects whether the text is HTML code.
	 * @return boolean true or false
	 * @param string $sText text to check
	 */
	public static function isHTML($sText) {
		// validate
		if (is_null($sText)) {return false;}

		// result
		return preg_match('/<[^<]+>/', $sText, $aMatches) != 0;
	}



	/**
	 * Tries to convert the var to integer.
	 * @return null|integer null or converted value
	 * @param float|integer|string $mValue value to be converted
	 * @param boolean $bAbsolute whether to use the absolute value
	 */
	public static function varToInt($mValue, $bAbsolute = true) {
		// validate
		if (is_null($mValue)) {return null;}
		if (!is_int($mValue) and !is_float($mValue) and !is_string($mValue)) {return null;}

		// convert
		$iValue = (int) $mValue;
		if ($bAbsolute) {$iValue = abs($iValue);}

		// result
		return $iValue;
	}
}