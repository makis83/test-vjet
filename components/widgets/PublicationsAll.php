<?php
namespace components\widgets;

use components\Widget;
use components\traits\Singleton;
use components\models\Publications;

/**
 * Generates block with all publications.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 23:10
 */
class PublicationsAll extends Widget {
	use Singleton;

	/**
	 * @inheritdoc
	 */
	public function run() {
		// get top publications
		$aPublications = Publications::instance()->all();
		if (empty($aPublications)) {return null;}

		// generate rows
		$sRows = $this->_rows($aPublications);

		// whole block
		return static::code('publications/all/container', [
			'%rows%' => $sRows
		]);
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
			$aRows[] = static::code('publications/all/row', [
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