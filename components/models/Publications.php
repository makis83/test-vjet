<?php
namespace components\models;

use components\Model;
use components\Config;
use components\traits\Singleton;
use components\helpers\HelperHtml;
use components\helpers\HelperText;
use components\helpers\HelperMisc;
use components\helpers\HelperDates;

/**
 * Works with publications.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 16:09
 */
class Publications extends Model {
	use Singleton;



	/**
	 * @inheritdoc
	 */
	public static function tableName(): string {
		return Config::DB['prefix'] . 'publications';
	}



	/**
	 * Generates array of top publications.
	 * @param integer $iPublications number of top publications
	 * @return array array of top publications
	 */
	public function top(int $iPublications = 5): array {
		// get table names
		$sTablePublications = self::tableName();
		$sTableUsers = Users::tableName();
		$sTableComments = Comments::tableName();

		// build query
		$sQuery = "
			SELECT
				{$sTablePublications}.id AS 'publication-id',
				{$sTablePublications}.subject AS 'publication-subject',
				{$sTablePublications}.introtext AS 'publication-introtext',
				{$sTablePublications}.content AS 'publication-content',
				{$sTablePublications}.added_on AS 'publication-date',
				{$sTablePublications}.added_by AS 'user-id',
				{$sTableUsers}.name AS 'user-name',
				{$sTableUsers}.email AS 'user-email',
				COUNT({$sTableComments}.id) AS comments
			FROM
				{$sTablePublications}
			LEFT JOIN
				{$sTableUsers}
			ON
				{$sTableUsers}.id = {$sTablePublications}.added_by
			LEFT JOIN
				{$sTableComments}
			ON
				{$sTableComments}.publication = {$sTablePublications}.id
			GROUP BY
				{$sTablePublications}.id
			ORDER BY
				comments DESC,
				{$sTablePublications}.added_on DESC
			LIMIT 0, {$iPublications}
		";

		// get publications
		$aPublications = static::$_oConnection->query($sQuery)->fetchAll(\PDO::FETCH_ASSOC);
		if (empty($aPublications)) {return $aPublications;}

		// fix array items
		foreach ($aPublications as $iIterator => &$aPublication) {
			// generate introtext
			if (mb_strlen($aPublication['publication-introtext'], Config::ENCODING)) {
				$sIntrotext = HelperHtml::encode($aPublication['publication-introtext']);
			} else {
				$sIntrotext = HelperText::intro($aPublication['publication-content'], 100, true);
				if (is_null($sIntrotext)) {
					$sIntrotext = strip_tags($aPublication['publication-content']);
				}
			}

			// update values
			$aPublication['publication-subject'] = HelperHtml::encode($aPublication['publication-subject']);
			$aPublication['publication-introtext'] = $sIntrotext;
			$aPublication['publication-date'] = HelperDates::date($aPublication['publication-date'], 'ru-RU', false, true);
			$aPublication['name-name'] = HelperHtml::encode($aPublication['name-name']);
			$aPublication['comments'] = $aPublication['comments'] . '&nbsp;' . HelperText::inflactWord($aPublication['comments'], ['комментарий', 'комментария', 'комментариев']);
		}

		// result
		$aPublications = HelperMisc::indexBy($aPublications, 'publication-id');
		return is_null($aPublications) ? [] : $aPublications;
	}



	/**
	 * Generates array of all publications.
	 * @return array array of all publications
	 */
	public function all(): array {
		// get table names
		$sTablePublications = self::tableName();
		$sTableUsers = Users::tableName();
		$sTableComments = Comments::tableName();

		// build query
		$sQuery = "
			SELECT
				{$sTablePublications}.id AS 'publication-id',
				{$sTablePublications}.subject AS 'publication-subject',
				{$sTablePublications}.introtext AS 'publication-introtext',
				{$sTablePublications}.content AS 'publication-content',
				{$sTablePublications}.added_on AS 'publication-date',
				{$sTablePublications}.added_by AS 'user-id',
				{$sTableUsers}.name AS 'user-name',
				{$sTableUsers}.email AS 'user-email',
				COUNT({$sTableComments}.id) AS comments
			FROM
				{$sTablePublications}
			LEFT JOIN
				{$sTableUsers}
			ON
				{$sTableUsers}.id = {$sTablePublications}.added_by
			LEFT JOIN
				{$sTableComments}
			ON
				{$sTableComments}.publication = {$sTablePublications}.id
			GROUP BY
				{$sTablePublications}.id
			ORDER BY
				{$sTablePublications}.added_on DESC
		";

		// get publications
		$aPublications = static::$_oConnection->query($sQuery)->fetchAll(\PDO::FETCH_ASSOC);
		if (empty($aPublications)) {return $aPublications;}

		// fix array items
		foreach ($aPublications as $iIterator => &$aPublication) {
			// generate introtext
			if (mb_strlen($aPublication['publication-introtext'], Config::ENCODING)) {
				$sIntrotext = HelperHtml::encode($aPublication['publication-introtext']);
			} else {
				$sIntrotext = HelperText::intro($aPublication['publication-content'], 100, true);
				if (is_null($sIntrotext)) {
					$sIntrotext = strip_tags($aPublication['publication-content']);
				}
			}

			// update values
			$aPublication['publication-subject'] = HelperHtml::encode($aPublication['publication-subject']);
			$aPublication['publication-introtext'] = $sIntrotext;
			$aPublication['publication-date'] = HelperDates::date($aPublication['publication-date'], 'ru-RU', false, true);
			$aPublication['user-name'] = HelperHtml::encode($aPublication['user-name']);
			$aPublication['comments'] = $aPublication['comments'] . '&nbsp;' . HelperText::inflactWord($aPublication['comments'], ['комментарий', 'комментария', 'комментариев']);
		}

		// result
		$aPublications = HelperMisc::indexBy($aPublications, 'publication-id');
		return is_null($aPublications) ? [] : $aPublications;
	}



	/**
	 * Gets info about the specified publication.
	 * @param integer $iPublication publication id
	 * @return null|array null or publication data
	 */
	public function publication(int $iPublication) {
		// validate
		if (!$iPublication) {return null;}

		// get all publications
		$aPublications = $this->all();
		if (!is_array($aPublications) or empty($aPublications) or !array_key_exists($iPublication, $aPublications)) {return null;}

		// result
		return $aPublications[$iPublication];
	}



	/**
	 * Adds a new publication.
	 * @param array $aParams params array
	 * <code>
	 * ['email' => '', 'name' => '', 'subject' => '', 'content' => '']
	 * </code>
	 * @return array result array
	 */
	public function add(array $aParams): array {
		// validate array
		if (!(is_array($aParams) and !empty($aParams))) {
			return HelperText::status('Входящий массив с данными о публикации пуст.', false);
		}

		// validate subject
		$sSubject = trim(htmlentities($aParams['subject'], ENT_COMPAT | ENT_QUOTES));

		try {
			$iPublication = $this->publicationId($sSubject);
			if ($iPublication) {
				return HelperText::status('Публикация с указанной темой уже имеется в базе.', false);
			}
		} catch (\InvalidArgumentException $oException) {
			return HelperText::status($oException->getMessage(), false);
		}

		// validate content
		$sContent = HelperHtml::tidy(strip_tags($aParams['content'], '<p><span><a><blockquote><strong><em><del><sup><sub><ul><ol><li><hr><h1><h2><h3><h4><h5><h6>'));
		if (!mb_strlen(trim(strip_tags($sContent)), Config::ENCODING)) {
			return HelperText::status('Контент публикации пуст.', false);
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

		// generate introtext
		$sIntrotext = HelperText::intro($sContent, 100, true);
		if (is_null($sIntrotext)) {$sIntrotext = '';}

		// add a new publication
		/** @noinspection SqlResolve */
		$sQuery = 'INSERT INTO ' . self::tableName() . ' (added_by, subject, introtext, content) VALUES (:added_by, :subject, :introtext, :content)';
		$oQuery = static::$_oConnection->prepare($sQuery);

		// try to insert
		try {
			static::$_oConnection->beginTransaction();
			$bSuccess = $oQuery->execute(['added_by' => $iUser, 'subject' => $sSubject, 'introtext' => $sIntrotext, 'content' => $sContent]);

			if ($bSuccess) {
				$sResponse = 'Новая публикация была успешно добавлена.';
				$iPublication = (int) static::$_oConnection->lastInsertId();
				static::$_oConnection->commit();
			} else {
				$sResponse = 'Не удалось добавить новую публикацию. Код ошибки: ' . static::$_oConnection->errorInfo()[0];
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



	/**
	 * Gets publication id by the subject.
	 * @param string $sSubject subject
	 * @return null|integer null or publication id
	 * @throws \InvalidArgumentException
	 */
	public function publicationId(string $sSubject) {
		// validate email
		if (!mb_strlen($sSubject, Config::ENCODING)) {
			throw new \InvalidArgumentException('Тема публикации не указана.');
		}

		// make query
		/** @noinspection SqlResolve */
		$sQuery = 'SELECT id FROM ' . self::tableName() . ' WHERE subject = :subject';
		$oQuery = static::$_oConnection->prepare($sQuery);
		$oQuery->execute(['subject' => $sSubject]);
		$sPublication = $oQuery->fetchColumn();

		// result
		return $sPublication ? (int) $sPublication : null;
	}
}