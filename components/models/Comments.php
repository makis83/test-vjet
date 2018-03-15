<?php
namespace components\models;

use components\helpers\HelperText;
use components\Model;
use components\Config;
use components\traits\Singleton;
use components\helpers\HelperDates;
use components\helpers\HelperHtml;
use components\helpers\HelperMisc;

/**
 * Works with comments.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 16:20
 */
class Comments extends Model {
	use Singleton;



	/**
	 * @inheritdoc
	 */
	public static function tableName(): string {
		return Config::DB['prefix'] . 'comments';
	}



	/**
	 * Generates array of publication comments.
	 * @param integer $iPublication publication id
	 * @return array comments array
	 */
	public function comments(int $iPublication): array {
		// validate
		if (!$iPublication) {return [];}

		// get table names
		$sTableComments = self::tableName();
		$sTableUsers = Users::tableName();

		// build query
		$sQuery = "
			SELECT
				{$sTableComments}.id AS 'comment-id',
				{$sTableComments}.comment AS 'comment-comment',
				{$sTableComments}.added_on AS 'comment-date',
				{$sTableComments}.added_by AS 'user-id',
				{$sTableUsers}.name AS 'user-name',
				{$sTableUsers}.email AS 'user-email'
			FROM
				{$sTableComments}
			LEFT JOIN
				{$sTableUsers}
			ON
				{$sTableUsers}.id = {$sTableComments}.added_by
			WHERE
				{$sTableComments}.publication = {$iPublication}
			ORDER BY
				{$sTableComments}.added_on ASC
		";

		// get comments
		$aComments = static::$_oConnection->query($sQuery)->fetchAll(\PDO::FETCH_ASSOC);
		if (empty($aComments)) {return $aComments;}

		// fix array items
		foreach ($aComments as $iIterator => &$aComment) {
			// update values
			$aComment['comment-date'] = HelperDates::date($aComment['comment-date'], 'ru-RU', false, true);
			$aComment['user-name'] = HelperHtml::encode($aComment['user-name']);
		}

		// result
		$aComments = HelperMisc::indexBy($aComments, 'comment-id');
		return is_null($aComments) ? [] : $aComments;
	}



	/**
	 * Get a single comment.
	 * @param integer $iPublication publication id
	 * @param integer $iComment comment id
	 * @return null|array null or comment array
	 */
	public function comment(int $iPublication, int $iComment) {
		// validate
		if (!$iPublication or !$iComment) {return null;}

		// get comments
		$aComments = $this->comments($iPublication);
		if (!is_array($aComments) or empty($aComments) or !array_key_exists($iComment, $aComments)) {return null;}

		// result
		return $aComments[$iComment];
	}



	/**
	 * Adds a new comment.
	 * @param integer $iPublication publication id
	 * @param array $aParams params array
	 * ['email' => '', 'name' => '', 'comment' => '']
	 * @return array result array
	 */
	public function add(int $iPublication, array $aParams): array {
		// validate array
		if (!(is_array($aParams) and !empty($aParams))) {
			return HelperText::status('Входящий массив с данными о комментарии пуст.', false);
		}

		// validate comment
		$sComment = HelperHtml::tidy(strip_tags($aParams['comment'], '<p><span><a><strong><em><del><sup><sub>'));
		if (!mb_strlen(trim(strip_tags($sComment)), Config::ENCODING)) {
			return HelperText::status('Комментарий пуст.', false);
		}

		// get user id
		try {
			$iUser = Users::instance()->userId($aParams['email']);
		} catch (\InvalidArgumentException $oException) {
			return HelperText::status($oException->getMessage(), false);
		}

		// add a new user
		if (!$iUser) {
			$aResult = Users::instance()->add(['name' => $aParams['name'], 'email' => $aParams['email']]);
			if (!$aResult['success']) {
				return HelperText::status($aResult['response'], false);
			}

			$iUser = $aResult['result'];
		}

		// add a new comment
		/** @noinspection SqlResolve */
		$sQuery = 'INSERT INTO ' . self::tableName() . ' (publication, added_by, comment) VALUES (:publication, :added_by, :comment)';
		$oQuery = static::$_oConnection->prepare($sQuery);

		// try to insert
		try {
			static::$_oConnection->beginTransaction();
			$bSuccess = $oQuery->execute(['publication' => $iPublication, 'added_by' => $iUser, 'comment' => $sComment]);

			if ($bSuccess) {
				$sResponse = 'Новый комментарий был упешно добавлен.';
				$iPublication = (int) static::$_oConnection->lastInsertId();
				static::$_oConnection->commit();
			} else {
				$sResponse = 'Не удалось добавить новый комментарий. Код ошибки: ' . static::$_oConnection->errorInfo()[0];
				$iPublication = null;
				static::$_oConnection->rollback();
			}

			// result
			return HelperText::status($sResponse, $bSuccess, $iPublication);
		} catch(\PDOException $oException) {
			static::$_oConnection->rollback();
			return HelperText::status($oException->getMessage(), false);
		}
	}
}