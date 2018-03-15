<?php
/**
 * Adds a new comment.
 * Created by PhpStorm.
 * User: max
 * Date: 15.03.18
 * Time: 16:16
 */

use components\Config;
use components\models\Comments;
use components\helpers\HelperMisc;
use components\helpers\HelperText;

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
$iPublication = (int) $_POST['publication'];
$sEmail = HelperMisc::receive($_POST['email'], false);
$sName = HelperMisc::receive($_POST['name'], false);
$sComment = HelperMisc::receive($_POST['comment'], false);

// try to save the comment
$aResult = Comments::instance()->add($iPublication, ['email' => $sEmail, 'name' => $sName, 'comment' => $sComment]);
HelperText::json($aResult['response'], $aResult['success'], $aResult['result']);