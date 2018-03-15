/**
 * Helper class.
 */
let Helper = (function () {
	// colors object
	let colors = {
		black: "#000000",
		blue: "#0000ff",
		brown: "#a52a2a",
		darkblue: "#00008b",
		darkcyan: "#008b8b",
		darkgreen: "#006400",
		darkmagenta: "#8b008b",
		darkolivegreen: "#556b2f",
		darkorange: "#ff8c00",
		darkorchid: "#9932cc",
		darkred: "#8b0000",
		darksalmon: "#e9967a",
		darkviolet: "#9400d3",
		fuchsia: "#ff00ff",
		green: "#008000",
		indigo: "#4b0082",
		lightgreen: "#90ee90",
		magenta: "#ff00ff",
		maroon: "#800000",
		navy: "#000080",
		olive: "#808000",
		orange: "#ffa500",
		purple: "#800080",
		violet: "#800080",
		red: "#ff0000"
	};

	// declare public variables and/or functions
	return {
		/**
		 * Generates object of random color's name and correspondent code.
		 * @returns {{name: String, rgb: String}}
		 */
		randomColor: function () {
			let result;
			let count = 0;
			for (let prop in colors) {
				if (Math.random() < 1/++count) {
					result = prop;
				}
			}

			// result
			return {
				name: result,
				rgb: colors[result]
			};
		},


		/**
		 * Scrolls to element.
		 * @param {jQuery} $element element
		 */
		scrollTo: function ($element) {
			$('html, body').animate({
				scrollTop: $element.offset().top
			}, 500);
		},



		/**
		 * updates carousel images.
		 */
		updateCarousel: function () {
			// update images in carousel
			const $carouselItems = $('#publications-top').find('.carousel-item');
			if ($carouselItems.length) {
				// define default src
				const src = 'https://dummyimage.com/800x300/xxxxxx/ffffff.png&text=+';

				// loop through carousel items
				let color, srcCurrent;
				$carouselItems.each(function () {
					// get random color
					color = Helper.randomColor();

					// update image src attribute
					srcCurrent = src.replace('xxxxxx', color['rgb'].slice(1));
					$(this).find('img').attr('src', srcCurrent);
				});
			}
		}
	};
})();


/**
 * Works with forms.
 */
let Forms = (function () {
	// declare public variables and/or functions
	return {
		/**
		 * Validates the form.
		 * @return boolean whether the form is valid
		 * @param {jQuery} $form form element
		 * @param {jQuery} $response response element
		 * @param {string} [exceptSelector] excluded elements
		 */
		validate: function ($form, $response, exceptSelector) {
			// look for required inputs
			let errors = 0, val;
			let $controls = $form.find('input[type="text"].required:visible, input[type="password"].required:visible, textarea.required:visible');

			// select except selectors
			if('undefined' !== typeof exceptSelector) {
				$controls = $controls.not(exceptSelector);
			}

			$controls.each(function () {
				val = $.trim($(this).val());
				if (!val.length) {
					Forms.blinkBorder($(this));
					errors++;
				}
			});

			// deal with selectboxes
			$form.find('select.required:visible').each(function () {
				val = parseInt($(this).val());
				if (!val) {
					Forms.blinkBorder($(this));
					errors++;
				}
			});

			// result
			if (errors) {
				// show the response
				if ($response !== null && typeof $response === 'object') {
					Alert.add($response, 'Необходимо заполнить указанные поля.', 'danger');
				}

				return false;
			} else {
				return true;
			}
		},



		/**
		 * Blinks with input border.
		 * @param {jQuery} $input input element
		 */
		blinkBorder: function ($input) {
			// validate element
			if (!($input instanceof jQuery)) {return;}

			// default params
			const color1 = '#ced4da';
			const color2 = '#f04124';
			const time1 = 150;
			const time2 = 200;

			// run animate
			$input.animate({borderColor: color2}, time1).delay(time2).animate({borderColor: color1}, time1).delay(time2).animate({borderColor: color2}, time1).delay(time2).animate({borderColor: color1}, time1).delay(time2).animate({borderColor: color2}, time1).delay(time2).animate({borderColor: color1}, time1);
		},



		/**
		 * Resets the form.
		 * @param {jQuery} $form form element
		 * @param {Boolean} [resethidden] whether to reset hidden inputs
		 */
		reset: function ($form, resethidden) {
			// validate
			if (!($form instanceof jQuery)) {return;}
			if (resethidden === undefined) {resethidden = true;}

			// inputs and textareas
			let def;
			let $inputs;
			if (resethidden) {
				$inputs = $form.find('input[type="text"], input[type="password"], input[type="hidden"], textarea');
			} else {
				$inputs = $form.find('input[type="text"], input[type="password"], textarea')
			}

			$inputs.each(function () {
				if ('undefined' !== typeof $(this).attr('data-default')) {
					def = $(this).attr('data-default');
					$(this).val(def);
				} else {
					$(this).val('');
				}
			});

			// selectboxes
			$form.find('select').each(function () {
				if ('undefined' !== typeof $(this).attr('data-default')) {
					def = $(this).attr('data-default');
					$(this).val(def);
				} else {
					$(this).val(0);
				}
			});
		}
	};
})();


/**
 * Generates alerts.
 */
let Alert = (function () {
	// declare public variables and/or functions
	return {
		/**
		 * Generates alert block.
		 * @param {jQuery} $response response block
		 * @param {String} message alert message
		 * @param {String} [type] alert's type (success, warning, danger)
		 */
		add: function ($response, message, type) {
			// validate
			if (!($response instanceof jQuery)) {return;}
			if (!message || message === undefined) {return;}
			if (null === type || undefined === type) {type = 'danger';}

			// generate the alert message
			const $alert = $('<div/>', {
				class: 'alert alert-' + type,
				html: message
			});

			// process
			let visible = $response.is(':visible');
			if (visible) {
				$response.html($alert[0].outerHTML);
			} else {
				$response.append($alert).slideDown(300);
			}
		},



		/**
		 * Removes the alert message.
		 * @param {jQuery} $response response block
		 */
		remove: function ($response) {
			// validate
			if (!($response instanceof jQuery)) {return;}

			// remove the block
			$response.empty().hide();
		}
	};
})();



/**
 * Allows to add publications and comments.
 */
let Content = (function () {
	// declare public variables and/or functions
	return {
		/**
		 * Adds a new publication.
		 */
		addPublication: function () {
			// get url
			const $form = $('#publication-form');
			const url = $form.attr('action');

			// response block
			const $response = $form.find('.response');

			// validate form
			if (!Forms.validate($form, $response)) {return;}

			// get field values
			const email = $.trim($('#publication-email').val());
			const name = $.trim($('#publication-name').val());
			const subject = $.trim($('#publication-subject').val());
			const content = $('#publication-content').trumbowyg('html');

			// make ajax query
			$.ajax({
				method: 'post',
				url: url,
				data: {email: email, name: name, subject: subject, content: content}
			})
			.done(function (data) {
				// validate response from server
				data = jsonValidate(data);

				// detect if adding was successful
				if (data['success']) {
					// reset form
					Forms.reset($form);
					$('#publication-content').trumbowyg('html', '');

					// update publications
					Update.publicationsTop();
					Update.publicationsAll(data['result']);
				}

				// show response
				Alert.add($response, data['response'], data['success'] ? 'success' : 'danger');
			})
			.fail(function () {
				Alert.add($response, 'Не удалось совершить AJAX-запрос.');
			})
		},



		/**
		 * Adds a new comment.
		 */
		addComment: function () {
			// get url
			const $form = $('#comment-form');
			const url = $form.attr('action');

			// response block
			const $response = $form.find('.response');

			// validate form
			if (!Forms.validate($form, $response)) {return;}

			// get field values
			const publication = $form.data('publication');
			const email = $.trim($('#comment-email').val());
			const name = $.trim($('#comment-name').val());
			const comment = $('#comment-comment').trumbowyg('html');

			// make ajax query
			$.ajax({
				method: 'post',
				url: url,
				data: {publication: publication, email: email, name: name, comment: comment}
			})
				.done(function (data) {
					// validate response from server
					data = jsonValidate(data);

					// detect if adding was successful
					if (data['success']) {
						// reset form
						Forms.reset($form);
						$('#comment-comment').trumbowyg('html', '');

						// update comments
						Update.comments(publication, data['result']);
					}

					// show response
					Alert.add($response, data['response'], data['success'] ? 'success' : 'danger');
				})
				.fail(function () {
					Alert.add($response, 'Не удалось совершить AJAX-запрос.');
				})
		}
	};
})();



/**
 * Updates HTML blocks after ajax requests.
 */
let Update = (function () {
	// declare public variables and/or functions
	return {
		/**
		 * Updates block with top publications.
		 */
		publicationsTop: function () {
			// get url
			const $wrapper = $('[data-container="publications-top"]');
			const $container = $('#publications-top');
			const url = $wrapper.attr('data-url');

			// get content
			$.ajax({
				method: 'post',
				url: url
			})
			.done(function (data) {
				// validate response from server
				data = jsonValidate(data);

				// detect if adding was successful
				if (data['success']) {
					// detect if carousel exists
					if ($container.length) {
						$container.carousel('dispose');
						$container.remove();
					}

					// add a new carousel
					$wrapper.append(data['result']);

					// reinit it
					$wrapper.find('> div').carousel();

					// update images
					Helper.updateCarousel();
				} else {
					console.warn('Не удалось получить код блока с наиболее популярными публикациями.');
				}
			})
			.fail(function () {
				console.warn('Не удалось совершить AJAX-запрос.');
			})
		},



		/**
		 * Updates block with all publications.
		 * @param {Number} publication last added publication id
		 */
		publicationsAll: function (publication) {
			// validate
			if (null === publication || undefined === publication) {
				console.error('Не удалось получить ID последней добавленной публикации.');
				return;
			}

			// get url
			const $wrapper = $('[data-container="publications-all"]');
			const $container = $('#publications-all');
			const url = $wrapper.attr('data-url');

			// get content
			$.ajax({
				method: 'post',
				url: url,
				data: {publication: publication}
			})
			.done(function (data) {
				// validate response from server
				data = jsonValidate(data);

				// detect if data exists
				if (data['success']) {
					// new publications item
					let item;

					// generate a new publication item
					Templates.chunk('publications/all/row',
						/** @callback chunkCallback */
						function (json) {
							if (undefined !== json && null !== json) {
								// new publication item
								item = json['result'];

								// detect if container exists
								if ($container.length) {
									// prepend a new publication
									$container.prepend(item);
								} else {
									// generate container
									Templates.chunk('publications/all/container',
										/** @callback chunkCallback */
										function (json) {
											if (undefined !== json && null !== json) {
												// paste html code
												$wrapper.html(json['result']);
											}
										}, {
											'%rows%': item,
										}
									);
								}
							}
						}, {
							'%id%': data['result']['publication-id'],
							'%subject%': data['result']['publication-subject'],
							'%date%': data['result']['publication-date'],
							'%introtext%': data['result']['publication-introtext'],
							'%user%': data['result']['user-name'],
							'%comments%': data['result']['comments'],
						}
					);
				} else {
					console.error('Не удалось получить информацию о последней добавленной публикации.');
				}
			})
			.fail(function () {
				console.error('Не удалось совершить AJAX-запрос.');
			})
		},



		/**
		 * Updates block with publication comments.
		 * @param {Number} publication publication id
		 * @param {Number} comment last added comment id
		 */
		comments: function (publication, comment) {
			// validate
			if (null === publication || undefined === publication) {
				console.error('Не удалось получить ID публикации.');
				return;
			}

			if (null === comment || undefined === comment) {
				console.error('Не удалось получить ID последнего добавленного комментария.');
				return;
			}

			// get url
			const $wrapper = $('[data-container="comments"]');
			const $container = $('#comments');
			const url = $wrapper.attr('data-url');

			// get content
			$.ajax({
				method: 'post',
				url: url,
				data: {publication: publication, comment: comment}
			})
			.done(function (data) {
				// validate response from server
				data = jsonValidate(data);

				// detect if data exists
				if (data['success']) {
					// new comment item
					let item;

					// generate a new publication item
					Templates.chunk('comments/publication/row',
						/** @callback chunkCallback */
						function (json) {
							if (undefined !== json && null !== json) {
								// new comment item
								item = json['result'];

								// detect if container exists
								if ($container.length) {
									// append a new comment
									$container.append(item);
								} else {
									// generate container
									Templates.chunk('comments/publication/container',
										/** @callback chunkCallback */
										function (json) {
											if (undefined !== json && null !== json) {
												// paste html code
												$wrapper.html(json['result']);
											}
										}, {
											'%rows%': item,
										}
									);
								}
							}
						}, {
							'%user%': data['result']['user-name'],
							'%date%': data['result']['comment-date'],
							'%comment%': data['result']['comment-comment']
						}
					);
				} else {
					console.error('Не удалось получить информацию о последнем добавленном комментарии.');
				}
			})
			.fail(function () {
				console.error('Не удалось совершить AJAX-запрос.');
			})
		}
	};
})();



/**
 * Works with templates.
 */
let Templates = (function () {
	// declare public variables and/or functions
	return {
		/**
		 * Gets template code.
		 */
		chunk: function (template, callback, params) {
			// validate
			if (undefined === template || undefined === callback || 'function' !== typeof callback) {
				console.error('Аргументы метода Templates.chunk() не указаны или указаны неверно.');
				return;
			}

			// url
			const url ='/ajax/template.php';

			// get JSON
			$.getJSON(url, {template: template, params: params}, function (data) {
				if (null === data) {
					console.error('Не удалось получить ответ сервера при попытке генерации шаблона ' + template + '.');
					return;
				}

				// run callback function
				callback(data);
			}).fail(function () {
				console.error('Не удалось получить код шаблона ' + template + '.');
			});
		}
	};
})();