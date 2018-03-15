<?php
/**
 * Adds a new publication.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 20:49
 */

use components\Config;
use components\helpers\HelperMisc;
use components\helpers\HelperText;
use components\models\Publications;

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

// get POST data
$sEmail = HelperMisc::receive($_POST['email'], false);
$sName = HelperMisc::receive($_POST['name'], false);
$sSubject = HelperMisc::receive($_POST['subject'], false);
$sContent = HelperMisc::receive($_POST['content'], false);

// try to save the publication
$aResult = Publications::instance()->add(['email' => $sEmail, 'name' => $sName, 'subject' => $sSubject, 'content' => $sContent]);
HelperText::json($aResult['response'], $aResult['success'], $aResult['result']);