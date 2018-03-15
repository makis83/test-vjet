<?php
namespace components\models;

use components\Model;
use components\Config;
use components\traits\Singleton;
use components\helpers\HelperText;

/**
 * Works with users.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 14:24
 */
class Users extends Model {
	use Singleton;



	/**
	 * @inheritdoc
	 */
	public static function tableName(): string {
		return Config::DB['prefix'] . 'users';
	}



	/**
	 * Generate array of available users.
	 * @return array array of available users
	 */
	public function users(): array {
		// get users
		$aUsers = static::$_oConnection->query('SELECT * FROM ' . self::tableName())->fetchAll(\PDO::FETCH_CLASS);
		return $aUsers;
	}



	/**
	 * Adds a new user.
	 * @param array $aParams params array
	 * <code>
	 * ['name' => '', 'email' => '']
	 * </code>
	 * @return array result array
	 */
	public function add(array $aParams): array {
		// validate array
		if (!(is_array($aParams) and !empty($aParams))) {
			return HelperText::status('Входящий массив пуст', false);
		}

		// validate name and email
		$sName = trim(htmlentities($aParams['name'], ENT_COMPAT | ENT_QUOTES));
		if (!mb_strlen($sName, Config::ENCODING) or !HelperText::isValidEmail($aParams['email'])) {
			return HelperText::status('Имя и/или email пользователя указаны неверно.', false);
		}

		// detect if this user already exists
		try {
			$iUser = $this->userId($aParams['email']);
			if ($iUser) {
				return HelperText::status('Пользователь с указанным email\'ом уже зарегистрирован.', false);
			}
		} catch (\InvalidArgumentException $oException) {
			return HelperText::status($oException->getMessage(), false);
		}

		// add a new user
		/** @noinspection SqlResolve */
		$sQuery = 'INSERT INTO ' . self::tableName() . ' (name, email) VALUES (:name, :email)';
		$oQuery = static::$_oConnection->prepare($sQuery);

		// try to insert
		try {
			static::$_oConnection->beginTransaction();
			$bSuccess = $oQuery->execute(['name' => $sName, 'email' => $aParams['email']]);

			if ($bSuccess) {
				$sResponse = 'Новый пользователь был успешно добавлен.';
				$iUser = (int) static::$_oConnection->lastInsertId();
				static::$_oConnection->commit();
			} else {
				$sResponse = 'Не удалось добавить нового пользователя. Код ошибки: ' . static::$_oConnection->errorInfo()[0];
				$iUser = null;
				static::$_oConnection->rollback();
			}

			// result
			return HelperText::status($sResponse, $bSuccess, $iUser);
		} catch(\PDOException $oException) {
			static::$_oConnection->rollback();
			return HelperText::status($oException->getMessage(), false);
		}
	}



	/**
	 * Gets user id by email.
	 * @param string $sEmail email
	 * @return null|integer null or user id
	 * @throws \InvalidArgumentException
	 */
	public function userId(string $sEmail) {
		// validate email
		if (!HelperText::isValidEmail($sEmail)) {
			throw new \InvalidArgumentException('Email указан неверно.');
		}

		// make query
		/** @noinspection SqlResolve */
		$sQuery = 'SELECT id FROM ' . self::tableName() . ' WHERE email = :email';
		$oQuery = static::$_oConnection->prepare($sQuery);
		$oQuery->execute(['email' => $sEmail]);
		$sUser = $oQuery->fetchColumn();

		// result
		return $sUser ? (int) $sUser : null;
	}
}