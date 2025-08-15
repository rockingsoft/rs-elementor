(function($){
	'use strict';

	function init($root){
		if(!$root || !$root.length) return;
		var $hidden = $root.find('.rs-varc-input');
		var $select = $root.find('.rs-varc-select');
		var $thumbs = $root.find('.rs-varc-thumbs');
		var syncEnabled = String($root.data('sync')) === '1';
		var variationsMap = {};
		try {
			var dataVar = $root.attr('data-variations');
			if (dataVar) { variationsMap = JSON.parse(dataVar); }
		} catch(e){ variationsMap = {}; }
		var $form = $();
		if(syncEnabled){
			// Try nearest standard containers first
			$form = $root.closest('.product, .elementor-widget, .elementor-section, body')
				.find('form.variations_form, .variations_form').first();
			// Global fallback
			if(!$form.length){
				$form = $('form.variations_form, .variations_form').first();
			}
		}

		function selectInForm(variationId){
			if(!syncEnabled || !$form.length) return;
			var attrs = variationsMap[String(variationId)] || variationsMap[variationId] || null;
			if(!attrs) return;
			// Set each attribute select and trigger change
			Object.keys(attrs).forEach(function(key){
				var val = attrs[key];
				var name = key; // already like 'attribute_pa_size'
				var $field = $form.find('[name="'+name+'"]');
				if($field.length){
					if($field.val() !== val){ $field.val(val); }
					$field.trigger('change');
				}
			});
			// Ask Woo to resolve the variation
			$form.trigger('check_variations');
		}

		// Helper to set value and trigger events for compatibility
		function setValue(val){
			$hidden.val(val).trigger('change');
			$root.trigger('rs_varc_change', [val]);
			selectInForm(val);
		}

		// Dropdown mode
		if($select.length){
			// Default to first option
			var firstVal = $select.find('option:first').val();
			if(firstVal){ setValue(firstVal); }
			$select.on('change', function(){
				setValue($(this).val());
			});
		}

		// Thumbnails mode
		if($thumbs.length){
			var $first = $thumbs.find('.rs-varc-thumb').first();
			if($first.length){
				setValue($first.data('value'));
			}
			$thumbs.on('click', '.rs-varc-thumb', function(){
				var $btn = $(this);
				$thumbs.find('.rs-varc-thumb').removeClass('is-active').attr('aria-pressed','false');
				$btn.addClass('is-active').attr('aria-pressed','true');
				setValue($btn.data('value'));
			});
		}

		// Reflect Woo form -> widget
		if(syncEnabled && $form.length){
			var reflectFromForm = function(){
				var vid = $form.find('input[name="variation_id"]').val();
				if(!vid){ return; }
				// update hidden
				$hidden.val(vid);
				// update dropdown
				if($select.length){
					if($select.val() !== String(vid)){
						$select.val(String(vid));
					}
				}
				// update thumbs
				if($thumbs.length){
					$thumbs.find('.rs-varc-thumb').each(function(){
						var $b=$(this);
						var isMatch = String($b.data('value'))===String(vid);
						$b.toggleClass('is-active', isMatch).attr('aria-pressed', isMatch?'true':'false');
					});
				}
			};

			$form.on('found_variation.wc-variation-form', function(){
				reflectFromForm();
			});
			$form.on('woocommerce_variation_has_changed', function(){
				reflectFromForm();
			});
			$form.on('reset_data', function(){
				// reset widget to first
				if($select.length){
					var v=$select.find('option:first').val();
					$select.val(v);
					setValue(v);
				}
				if($thumbs.length){
					var $f=$thumbs.find('.rs-varc-thumb').first();
					if($f.length){
						$thumbs.find('.rs-varc-thumb').removeClass('is-active').attr('aria-pressed','false');
						$f.addClass('is-active').attr('aria-pressed','true');
						setValue($f.data('value'));
					}
				}
			});

			// Initial reflect if form already has a selection
			setTimeout(reflectFromForm, 0);
		}
	}

	function mountAll(){
		$('.rs-variation-chooser').each(function(){ init($(this)); });
	}

	// Standard load
	$(document).ready(mountAll);

	// Elementor preview support
	if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
		window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope){
			$scope.find('.rs-variation-chooser').each(function(){ init($(this)); });
		});
	}

})(jQuery);
