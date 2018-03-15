<?php
/**
 * Gets template code.
 * Created by PhpStorm.
 * User: max
 * Date: 15.03.18
 * Time: 11:35
 */

use components\Config;
use components\helpers\HelperMisc;
use components\helpers\HelperText;
use components\Widget;

require "../vendor/autoload.php";

// headers for JSON
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

// IE offers to save 'application/json' instead of displaying it
if (HelperMisc::isIE()) {
	header('Content-Type: text/plain; charset="' . Config::ENCODING . '"');
} else {
	header('Content-Type: application/json; charset="' . Config::ENCODING . '"');
}

// detects whether the request was made via AJAX
if ('XMLHttpRequest' !== $_SERVER['HTTP_X_REQUESTED_WITH'] and !HelperMisc::isIE()) {
	HelperText::json('Доступ возможен только через AJAX-запрос.', false, null);
}

// get data
$sTemplate = HelperMisc::receive($_GET['template'], false);
$aParams = HelperMisc::receive($_GET['params'], false);

// generate template's code
$sCode = Widget::code($sTemplate, is_null($aParams) ? [] : $aParams);
HelperText::json(null, is_null($sCode) ? false : true, $sCode);