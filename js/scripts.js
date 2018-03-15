/**
 * Custom JS scripts.
 */
$(document).ready(function () {
	// update images in carousel
	Helper.updateCarousel();

	// init WYSIWYG editor
	$('#publication-content').trumbowyg({
		btns: [
			['undo', 'redo'],
			['formatting'],
			['strong', 'em', 'del'],
			['superscript', 'subscript'],
			['link'],
			['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
			['unorderedList', 'orderedList'],
			['horizontalRule'],
			['removeformat'],
			['fullscreen']
		],
		autogrow: true,
		lang: 'ru',
		svgPath: '/img/trumbowyg-icons.svg',
		resetCss: true,
		removeformatPasted: true
	});

	$('#comment-comment').trumbowyg({
		btns: [
			['undo', 'redo'],
			['strong', 'em', 'del'],
			['superscript', 'subscript'],
			['link'],
			['unorderedList', 'orderedList'],
			['removeformat']
		],
		autogrow: true,
		lang: 'ru',
		svgPath: '/img/trumbowyg-icons.svg',
		resetCss: true,
		removeformatPasted: true
	});
});



/**
 * Validates JSON.
 * @return {Object} JSON object
 * @param {String|Object} data JSON object
 */
function jsonValidate(data) {
	if (typeof data !== 'object') {
		// if it's not an object trying to parse it as JSON
		let pattern = /({.+})/;
		let arr = pattern.exec(data);
		data = jQuery.parseJSON(arr[0]);

		// result
		return data;
	} else {
		// original data
		return data;
	}
}