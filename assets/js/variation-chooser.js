(function($){
	'use strict';

	function init($root){
		if(!$root || !$root.length) return;
		var attr = $root.data('attribute');
		var $hidden = $root.find('.rs-varc-input');
		var $select = $root.find('.rs-varc-select');
		var $thumbs = $root.find('.rs-varc-thumbs');

		// Helper to set value and trigger events for compatibility
		function setValue(val){
			$hidden.val(val).trigger('change');
			$root.trigger('rs_varc_change', [val]);
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
