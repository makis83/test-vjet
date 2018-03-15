<?php
namespace components\widgets;

use components\Widget;
use components\models\Comments;
use components\traits\Singleton;

/**
 * Generates comments block for publication.
 * Created by PhpStorm.
 * User: max
 * Date: 15.03.18
 * Time: 15:20
 */
class CommentsPublication extends Widget {
	use Singleton;

	/**
	 * @var null|integer $iPublication publication id
	 */
	public $iPublication;



	/**
	 * @inheritdoc
	 */
	public function run() {
		// get publication id
		if (!$this->iPublication) {
			$this->iPublication = (int) $_GET['id'];
			if (!$this->iPublication) {return null;}
		}

		// get comments
		$aComments = Comments::instance()->comments($this->iPublication);
		if (empty($aComments)) {return null;}

		// whole block
		return static::code('comments/publication/container', [
			'%rows%' => $this->_rows($aComments)
		]);
	}



	/**
	 * Generates comment rows.
	 * @param array $aComments comments array
	 * @return string HTML code
	 */
	private function _rows(array $aComments): string {
		// loop through comments
		$aRows = [];
		$iIterator = 0;
		foreach ($aComments as $iComment => $aComment) {
			// add row
			$aRows[] = static::code('comments/publication/row', [
				'%user%' => $aComment['user-name'],
				'%date%' => $aComment['comment-date'],
				'%comment%' => $aComment['comment-comment']
			]);

			// increase iterator's value
			$iIterator++;
		}

		// result
		return implode(PHP_EOL, $aRows);
	}
}