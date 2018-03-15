<?php
namespace components\helpers;

use tidy as Tidy;
use components\Config;

/**
 * Works with HTML.
 * Created by PhpStorm.
 * User: max
 * Date: 14.03.18
 * Time: 16:54
 */
class HelperHtml {
	/**
	 * Encodes the text (used for non-users content).
	 * @return null|string null or encoded string
	 * @param string $sText text to be encoded
	 * @uses Html::encode()
	 * @uses HelperText::isHTML()
	 */
	public static function encode($sText) {
		// validate
		$sText = trim($sText);
		if (!mb_strlen($sText, Config::ENCODING)) {return null;}

		// detect if text consists of HTML
		if (HelperText::isHTML($sText)) {
			return $sText;
		} else {
			// convert all entities to chars and vice versa
			$sText = html_entity_decode($sText, ENT_QUOTES | ENT_HTML5, Config::ENCODING);

			// encode the string
			return htmlentities($sText);
		}
	}



	/**
	 * Tidies the HTML.
	 * @param string $sHTML original HTML code
	 * @return null|string null or HTML code
	 * @param boolean $bRepair whether to repair the HTML
	 */
	public static function tidy($sHTML, $bRepair = true) {
		// validate
		if (!mb_strlen($sHTML, Config::ENCODING)) {return null;}

		// config
		$aConfig = array(
			'bare' => true, // strip Microsoft specific HTML from Word 2000 documents
			'clean' => false, // strip out surplus presentational tags and attributes replacing them by style rules and structural markup as appropriate
			'drop-empty-paras' => true, // if Tidy should discard empty paragraphs
			'drop-font-tags' => true, // if Tidy should discard <FONT> and <CENTER> tags without creating the corresponding style rules
			'drop-proprietary-attributes' => true, // if Tidy should strip out proprietary attributes, such as MS data binding attributes
			'join-classes' => true, // specifies if Tidy should combine class names to generate a single new class name, if multiple class assignments are detected on an element
			'logical-emphasis' => true, // if Tidy should replace any occurrence of <I> by <EM> and any occurrence of <B> by <STRONG>
			'preserve-entities' => true, // if Tidy should preserve the well-formed entitites as found in the input
			'show-body-only' => true, // if Tidy should print only the contents of the body tag as an HTML fragment
			'word-2000' => true, // if Tidy should go to great pains to strip out all the surplus stuff Microsoft Word 2000 inserts when you save Word documents as "Web pages"
			'indent-spaces' => 0, // specify the number of spaces Tidy uses to indent content, when indentation is enabled
			'input-encoding' => 'utf8', // specify the character encoding Tidy uses for the input
			'output-encoding' => 'utf8', // specifies the character encoding Tidy uses for the output
			'output-xhtml' => true, // specifies if Tidy should generate pretty printed output, writing it as extensible HTML
			'wrap' => 0
		);

		// parse
		$oTidy = new Tidy;
		$oTidy->parseString($sHTML, $aConfig, 'utf8');

		// try to repair
		if ($bRepair) {
			$oTidy->cleanRepair();
		}

		// result
		return tidy_get_output($oTidy);
	}
}