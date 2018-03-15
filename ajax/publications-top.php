<?php
/**
 * Gets top publications.
 * Created by PhpStorm.
 * User: max
 * Date: 15.03.18
 * Time: 12:08
 */

use components\Config;
use components\helpers\HelperMisc;
use components\helpers\HelperText;
use components\widgets\PublicationsTop;

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

// generate top publications block
$sPublications = PublicationsTop::instance()->run();
HelperText::json(null, true, $sPublications);