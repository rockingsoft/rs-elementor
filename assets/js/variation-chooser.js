(function ($) {
	'use strict';

	function init($root) {
		if (!$root || !$root.length) return;
		var $hidden = $root.find('.rs-varc-input');
		var $select = $root.find('.rs-varc-select');
		var $thumbs = $root.find('.rs-varc-thumbs');
		var $singleLineWrap = $root.find('.rs-varc-thumbs-wrap.rs-varc-singleline');
		var syncEnabled = String($root.data('sync')) === '1';
		var variationsMap = {};
		try {
			var dataVar = $root.attr('data-variations');
			if (dataVar) { variationsMap = JSON.parse(dataVar); }
		} catch (e) { variationsMap = {}; }
		var $form = $();
		if (syncEnabled) {
			// Try nearest standard containers first
			$form = $root.closest('.product, .elementor-widget, .elementor-section, body')
				.find('form.variations_form, .variations_form').first();
			// Global fallback
			if (!$form.length) {
				$form = $('form.variations_form, .variations_form').first();
			}
			// Tag the form so we can hide default selectors via scoped CSS
			if ($form.length) {
				$form.addClass('rs-varc-hide-defaults');
			}
		}

		function selectInForm(variationId) {
			if (!syncEnabled || !$form.length) return;
			if (!variationId) {
				// Clear Woo form state when no selection
				$form.trigger('reset_data');
				return;
			}
			var attrs = variationsMap[String(variationId)] || variationsMap[variationId] || null;
			if (!attrs) return;
			// Set each attribute select and trigger change
			Object.keys(attrs).forEach(function (key) {
				var val = attrs[key];
				var name = key; // already like 'attribute_pa_size'
				var $field = $form.find('[name="' + name + '"]');
				if ($field.length) {
					if ($field.val() !== val) { $field.val(val); }
					$field.trigger('change');
				}
			});
			// Ask Woo to resolve the variation
			$form.trigger('check_variations');
		}

		// Clear UI helpers
		function clearThumbs() {
			if ($thumbs.length) {
				$thumbs.find('.rs-varc-thumb').removeClass('is-active').attr('aria-pressed', 'false');
			}
		}
		function clearSelect() {
			if ($select.length) {
				$select.val('');
			}
		}

		// Helper to set value and trigger events for compatibility
		function setValue(val) {
			if (!val) {
				clearThumbs();
				clearSelect();
				$hidden.val('').trigger('change');
				$root.trigger('rs_varc_change', ['']);
				selectInForm('');
				return;
			}
			$hidden.val(val).trigger('change');
			$root.trigger('rs_varc_change', [val]);
			selectInForm(val);
			// When in single-line mode, ensure selected is visible
			if ($singleLineWrap.length && $thumbs.length) {
				var $active = $thumbs.find('.rs-varc-thumb.is-active');
				if ($active.length) {
					try { $active[0].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' }); } catch (e) { }
				}
			}
		}

		// Dropdown mode
		if ($select.length) {
			$select.on('change', function () {
				setValue($(this).val());
			});
		}

		// Thumbnails mode
		if ($thumbs.length) {
			$thumbs.on('click', '.rs-varc-thumb', function () {
				var $btn = $(this);
				$thumbs.find('.rs-varc-thumb').removeClass('is-active').attr('aria-pressed', 'false');
				$btn.addClass('is-active').attr('aria-pressed', 'true');
				setValue($btn.data('value'));
			});

			// Single-line navigation buttons
			if ($singleLineWrap.length) {
				var $prev = $singleLineWrap.find('.rs-varc-prev');
				var $next = $singleLineWrap.find('.rs-varc-next');
				var el = $thumbs.get(0);
				var scrollBy = function (dir) {
					if (!el) return;
					var amount = Math.max(el.clientWidth * 0.8, 80);
					el.scrollBy({ left: dir * amount, behavior: 'smooth' });
				};

				// Update nav visibility and disabled state
				var updateNav = function () {
					if (!el) return;
					var canScroll = el.scrollWidth > el.clientWidth + 1; // tolerance
					if (!canScroll) {
						$prev.addClass('is-hidden').attr('aria-disabled', 'true');
						$next.addClass('is-hidden').attr('aria-disabled', 'true');
						return;
					}
					$prev.removeClass('is-hidden');
					$next.removeClass('is-hidden');
					var maxScrollLeft = el.scrollWidth - el.clientWidth - 1;
					var atStart = el.scrollLeft <= 1;
					var atEnd = el.scrollLeft >= maxScrollLeft;
					$prev.attr('aria-disabled', atStart ? 'true' : 'false');
					$next.attr('aria-disabled', atEnd ? 'true' : 'false');
				};

				$prev.on('click', function () { if ($prev.attr('aria-disabled') !== 'true') scrollBy(-1); });
				$next.on('click', function () { if ($next.attr('aria-disabled') !== 'true') scrollBy(1); });

				// Listeners
				$thumbs.on('scroll', updateNav);
				$(window).on('resize', updateNav);
				// Initial after layout
				setTimeout(updateNav, 0);
			}
		}

		// Reflect Woo form -> widget
		if (syncEnabled && $form.length) {
			var reflectFromForm = function () {
				var vid = $form.find('input[name="variation_id"]').val();
				if (!vid) {
					// Clear UI when no resolved variation
					clearThumbs();
					clearSelect();
					$hidden.val('');
					return;
				}
				// update hidden
				$hidden.val(vid);
				// update dropdown
				if ($select.length) {
					if ($select.val() !== String(vid)) {
						$select.val(String(vid));
					}
				}
				// update thumbs
				if ($thumbs.length) {
					$thumbs.find('.rs-varc-thumb').each(function () {
						var $b = $(this);
						var isMatch = String($b.data('value')) === String(vid);
						$b.toggleClass('is-active', isMatch).attr('aria-pressed', isMatch ? 'true' : 'false');
					});
						// ensure active visible in single-line and refresh nav state
					if ($singleLineWrap.length) {
						var $active = $thumbs.find('.rs-varc-thumb.is-active');
						if ($active.length) {
							try { $active[0].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' }); } catch (e) { }
						}
						// update nav after scrolling
						setTimeout(function(){ $thumbs.trigger('scroll'); }, 50);
					}
				}
			};

			$form.on('found_variation.wc-variation-form', function () {
				reflectFromForm();
			});
			$form.on('woocommerce_variation_has_changed', function () {
				reflectFromForm();
			});
			$form.on('reset_data', function () {
				// Clear widget selection on Woo reset
				clearThumbs();
				clearSelect();
				$hidden.val('');
			});

			// Initial reflect based on Woo defaults (if any)
			setTimeout(function () {
				$form.trigger('check_variations');
				setTimeout(reflectFromForm, 0);
			}, 0);
		}

	}

	function mountAll() {
		$('.rs-variation-chooser').each(function () { init($(this)); });
	}

	// Standard load
	$(document).ready(mountAll);

	// Elementor preview support
	if (window.elementorFrontend && window.elementorFrontend.hooks) {
		window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
			$scope.find('.rs-variation-chooser').each(function () { init($(this)); });
		});
	}

})(jQuery);
