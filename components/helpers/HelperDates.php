<?php
namespace components\helpers;

/**
 * Works with dates.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 13:55
 */
class HelperDates {
	/*
	 * Generates array of last N day dates.
	 * @return array array of last N day dates
	 */
	public static function getLastNDays(int $iDays = 7, string $sFormat = 'Y-m-d'): array {
		// initial array
		$aDates = [];

		// loop
		for ($iIter = 0; $iIter < $iDays; $iIter++) {
			$aDates[] = date($sFormat, strtotime($iIter . ' days ago'));
		}

		// result
		return array_reverse($aDates);
	}



	/**
	 * Gets weekday name for the specified date and locale.
	 * @return null|string null or weekday name
	 * @param null|string $sDate date ('Y-m-d' or 'Y-m-d H:i:s' format)
	 * @param null|string $sLocale locale
	 * @see http://userguide.icu-project.org/formatparse/datetime
	 */
	public static function weekday($sDate = null, $sLocale = null) {
		// get date
		if (is_null($sDate)) {
			$sDate = date('Y-m-d');
		}

		// validate
		if (!HelperText::isValidTimestamp($sDate, 'Y-m-d') and !HelperText::isValidTimestamp($sDate, 'Y-m-d H:i:s')) {return null;}

		// get locale
		if (is_null($sLocale)) {
			$sLocale = 'ru-RU';
		}

		// create intl formatter object
		$oDateTime = new \DateTime($sDate);
		$oIntlDateFormatter = new \IntlDateFormatter($sLocale, \IntlDateFormatter::SHORT, \IntlDateFormatter::NONE, null, null, 'cccc');

		// result
		return $oIntlDateFormatter->format($oDateTime);
	}



	/**
	 * Generates translation for current date and locale.
	 * @return null|string null or translated date
	 * @param null|string $sDate date ('Y-m-d' or 'Y-m-d H:i:s' format)
	 * @param null|string $sLocale locale
	 * @param boolean $bShowDoW whether to show the day of the week
	 * @param boolean $bShowTime whether to show time
	 * @see http://userguide.icu-project.org/formatparse/datetime
	 */
	public static function date($sDate = null, $sLocale = null, $bShowDoW = false, $bShowTime = false) {
		// get date
		if (is_null($sDate)) {
			$sDate = date('Y-m-d');
		}

		// validate
		if (!HelperText::isValidTimestamp($sDate, 'Y-m-d') and !HelperText::isValidTimestamp($sDate, 'Y-m-d H:i:s')) {return null;}

		// get locale
		if (is_null($sLocale)) {
			$sLocale = 'ru-RU';
		}

		// create intl formatter object
		$oDateTime = new \DateTime($sDate);

		// get date type
		if ($bShowDoW) {
			$iDateType = \IntlDateFormatter::TRADITIONAL;
		} else {
			$iDateType = \IntlDateFormatter::MEDIUM;
		}

		// get time type
		if ($bShowTime) {
			$iTimeType = \IntlDateFormatter::SHORT;
		} else {
			$iTimeType = \IntlDateFormatter::NONE;
		}

		$oIntlDateFormatter = new \IntlDateFormatter($sLocale, $iDateType, $iTimeType, null, null);

		// result
		return $oIntlDateFormatter->format($oDateTime);
	}
}