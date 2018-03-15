<?php
namespace components;

/**
 * Main widgets class.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 22:40
 */
abstract class Widget {
	/**
	 * Generates widget content.
	 * @return null|string null or HTML code
	 */
	abstract function run();



	/**
	 * Generates array of templates.
	 * @return array array of templates
	 */
	public static function templates() {
		return [
			'publications' => [
				'top' => [
					'container' => dirname(__FILE__) . '/widgets/templates/publications/top/container.html',
					'indicator' => dirname(__FILE__) . '/widgets/templates/publications/top/indicator.html',
					'row' => dirname(__FILE__) . '/widgets/templates/publications/top/row.html'
				],
				'all' => [
					'container' => dirname(__FILE__) . '/widgets/templates/publications/all/container.html',
					'row' => dirname(__FILE__) . '/widgets/templates/publications/all/row.html'
				]
			],
			'comments' => [
				'publication' => [
					'container' => dirname(__FILE__) . '/widgets/templates/comments/publication/container.html',
					'row' => dirname(__FILE__) . '/widgets/templates/comments/publication/row.html'
				]
			]
		];
	}



	/**
	 * Gets path to template.
	 * @return null|string null ot path to template
	 * @param string $sTemplate special path to template (i.e. 'publications/top/container')
	 * @param array $aTemplates module templates
	 */
	public static function template($sTemplate, $aTemplates = null) {
		// get templates
		if (!is_array($aTemplates)) {
			$aTemplates = static::templates();
			if (empty($aTemplates)) {return null;}
		}

		// explode template name into parts
		$aParts = explode('/', $sTemplate);

		// detect if parts array consists more than 1 elements
		if (count($aParts) > 1) {
			// loop through array and get path to template
			foreach ($aParts as $sPart) {
				// detect if key exists
				if (array_key_exists($sPart, $aTemplates)) {
					// detect if it's array
					if (is_array($aTemplates[$sPart])) {
						// generate template's name
						$aTmp = $aParts;
						unset($aTmp[0]);
						$sName = implode('/', $aTmp);

						// recursive calling
						return self::template($sName, $aTemplates[$sPart]);
					} else {
						return $aTemplates[$sPart];
					}
				} else {
					return null;
				}
			}
		} else {
			return array_key_exists($sTemplate, $aTemplates) ? $aTemplates[$sTemplate] : null;
		}

		// default result
		return null;
	}



	/**
	 * Generates HTML code.
	 * @param string $sTemplate full path to template file or special path described in templates() method
	 * @param array $aPlaceholders placeholders
	 * <code>
	 * ['[+placeholder1+]' => '', '[+placeholder2+]' => '']
	 * </code>
	 * @see Widget::templates()
	 * @return null|string null or HTML code
	 */
	public static function code(string $sTemplate, array $aPlaceholders) {
		// detect if file exists
		$sTemplate = self::template($sTemplate);
		if (is_null($sTemplate)) {return null;}

		// get HTML code
		$sCode = file_get_contents($sTemplate);

		// result
		return strtr($sCode, $aPlaceholders);
	}
}