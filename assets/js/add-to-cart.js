(function($){
  'use strict';

  function findContext($btn){
    var $scope = $btn.closest('.product, .elementor-widget, .elementor-section, body');
    var $form = $scope.find('form.variations_form, .variations_form').first();
    var $chooser = $scope.find('.rs-variation-chooser').first();
    return { $scope: $scope, $form: $form, $chooser: $chooser };
  }

  function getQuantity($scope){
    var $qty = $scope.find('input.qty[name="quantity"], input[name="quantity"]').first();
    var q = parseFloat($qty.val());
    if (isNaN(q) || q <= 0) q = 1;
    return q;
  }

  function getVariationSelection(ctx){
    // Prefer our chooser hidden input
    var vid = '';
    if (ctx.$chooser && ctx.$chooser.length) {
      var $hidden = ctx.$chooser.find('.rs-varc-input');
      if ($hidden.length) vid = String($hidden.val() || '');
    }
    // Fallback to Woo variations_form
    if (!vid && ctx.$form && ctx.$form.length) {
      vid = String(ctx.$form.find('input[name="variation_id"]').val() || '');
    }
    return vid;
  }

  function getAttributes(ctx){
    var attrs = {};
    if (ctx.$form && ctx.$form.length) {
      ctx.$form.find('select[name^="attribute_"]').each(function(){
        if (this.name && this.value) attrs[this.name] = this.value;
      });
    }
    return attrs;
  }

  function setLoading($btn, on){
    if (on) {
      $btn.prop('disabled', true).attr('aria-disabled', 'true').addClass('is-loading');
      $btn.find('.rs-atc-loading').show();
    } else {
      $btn.prop('disabled', false).attr('aria-disabled', 'false').removeClass('is-loading');
      $btn.find('.rs-atc-loading').hide();
    }
  }

  function showMessage($btn, msg, type){
    // Minimal inline message near button
    var $wrap = $btn.closest('.rs-add-to-cart-wrapper');
    if (!$wrap.length) { alert(msg); return; }
    var $box = $wrap.find('.rs-atc-message');
    if (!$box.length) { $box = $('<div class="rs-atc-message" />').appendTo($wrap); }
    $box.removeClass('is-error is-success').addClass(type === 'error' ? 'is-error' : 'is-success').text(msg).fadeIn(150);
    setTimeout(function(){ $box.fadeOut(200); }, 2500);
  }

  function ajaxAddToCart(data, $btn){
    var url = (window.wc_add_to_cart_params && window.wc_add_to_cart_params.wc_ajax_url)
      ? window.wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'add_to_cart')
      : (window.ajaxurl ? window.ajaxurl : (window.location.origin + '/?wc-ajax=add_to_cart'));

    return $.ajax({
      url: url,
      method: 'POST',
      data: data,
      dataType: 'json'
    }).done(function(response){
      if (!response || response.error) {
        throw new Error('error');
      }
      // Update fragments if provided
      if (response.fragments) {
        $.each(response.fragments, function(selector, html){
          $(selector).replaceWith(html);
        });
      }
      // Trigger Woo events for compatibility
      $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $btn]);
    });
  }

  function buildPostForm(data, action){
    var $form = $('<form method="post" />');
    if (action) { $form.attr('action', action); }
    $.each(data, function(k, v){
      if (v === undefined || v === null) return;
      $('<input/>', { type: 'hidden', name: k, value: v }).appendTo($form);
    });
    return $form;
  }

  function handleClick(e){
    var $btn = $(this);
    var $wrap = $btn.closest('.rs-add-to-cart-wrapper');
    var productId = parseInt($wrap.data('product-id'), 10) || 0;
    var productType = String($wrap.data('product-type') || 'simple');
    var mode = String($btn.data('mode') || 'ajax');
    var msgSelect = String($btn.data('msg-select') || 'Please select a variation first.');
    var msgAdded = String($btn.data('msg-added') || 'Added to cart!');
    var msgError = String($btn.data('msg-error') || 'Something went wrong. Please try again.');

    if (!productId) return;

    var ctx = findContext($btn);

    // Validate variation selection for variable products
    var variationId = '';
    var attrs = {};
    if (productType === 'variable') {
      variationId = getVariationSelection(ctx);
      if (!variationId) {
        showMessage($btn, msgSelect, 'error');
        return;
      }
      attrs = getAttributes(ctx);
    }

    setLoading($btn, true);

    var quantity = getQuantity(ctx.$scope);

    if (mode === 'ajax') {
      var data = {
        product_id: productId,
        quantity: quantity
      };
      if (variationId) {
        data.variation_id = variationId;
        // append attributes
        $.each(attrs, function(k, v){ data[k] = v; });
      }

      ajaxAddToCart(data, $btn)
        .done(function(){ showMessage($btn, msgAdded, 'success'); })
        .fail(function(){ showMessage($btn, msgError, 'error'); })
        .always(function(){ setLoading($btn, false); });

    } else {
      // Classic POST: build and submit a form
      var payload = { 'add-to-cart': productId, quantity: quantity };
      if (variationId) {
        payload.product_id = productId;
        payload.variation_id = variationId;
        $.each(attrs, function(k, v){ payload[k] = v; });
      }
      var action = String($wrap.data('product-url') || '');
      var $form = buildPostForm(payload, action).hide();
      $('body').append($form);
      $form.trigger('submit');
      // Keep loading state; the page will navigate
    }
  }

  function mount(){
    $(document).on('click', '.rs-atc-btn', handleClick);
  }

  $(mount);

  // Elementor preview support
  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function($scope){
      // delegated events already cover dynamic content
    });
  }

})(jQuery);
