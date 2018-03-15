<?php
namespace components\widgets;

use components\Widget;
use components\traits\Singleton;
use components\models\Publications;

/**
 * Generates block with top publications.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 22:39
 */
class PublicationsTop extends Widget {
	use Singleton;

	/**
	 * @var integer $iPublications number of top publications
	 */
	public $iPublications = 5;



	/**
	 * @inheritdoc
	 */
	public function run() {
		// get top publications
		$aPublications = Publications::instance()->top($this->iPublications);
		if (empty($aPublications)) {return null;}

		// generate indicators and rows
		$sIndicators = $this->_indicators($aPublications);
		$sRows = $this->_rows($aPublications);

		// whole block
		return static::code('publications/top/container', [
			'%indicators%' => $sIndicators,
			'%rows%' => $sRows
		]);
	}



	/**
	 * Generates HTML code of indicators.
	 * @param array $aPublications publications array
	 * @return string HTML code
	 */
	private function _indicators(array $aPublications): string {
		// loop through publications
		$aRows = [];
		foreach ($aPublications as $iIterator => $aPublication) {
			// add indicator
			$aRows[] = static::code('publications/top/indicator', [
				'%iterator%' => $iIterator,
				'%class%' => $iIterator ? null : ' class="active"'
			]);
		}

		// result
		return implode(PHP_EOL, $aRows);
	}



	/**
	 * Generates HTML code of publication rows.
	 * @param array $aPublications publications array
	 * @return string HTML code
	 */
	private function _rows(array $aPublications): string {
		// loop through publications
		$aRows = [];
		$iIterator = 0;
		foreach ($aPublications as $iPublication => $aPublication) {
			// add row
			$aRows[] = static::code('publications/top/row', [
				'%iterator%' => $iIterator,
				'%class%' => $iIterator ? null : ' active',
				'%id%' => $iPublication,
				'%subject%' => $aPublication['publication-subject'],
				'%introtext%' => $aPublication['publication-introtext'],
				'%date%' => $aPublication['publication-date'],
				'%user%' => $aPublication['user-name'],
				'%comments%' => $aPublication['comments']
			]);

			// increase iterator's value
			$iIterator++;
		}

		// result
		return implode(PHP_EOL, $aRows);
	}
}