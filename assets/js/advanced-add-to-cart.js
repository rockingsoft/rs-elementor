(function ($) {
  'use strict';

  // Ensure Woo's variation JS is initialized on our forms on DOM ready
  $(function () {
    if ($.fn.wc_variation_form) {
      $('.rs-advanced-add-to-cart form.variations_form').each(function () {
        $(this).wc_variation_form();
      });
    }
    // Prevent native submit by changing button type for loop variable forms
    $('.rs-advanced-add-to-cart.rs-context-loop form.variations_form .single_add_to_cart_button').attr('type', 'button');
  });

  function getAjaxEndpoint(endpoint) {
    if (typeof wc_add_to_cart_params !== 'undefined' && wc_add_to_cart_params.wc_ajax_url) {
      return wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', endpoint);
    }
    // Fallback
    return window.location.href;
  }

  function collectVariationFormData($form) {
    var data = {};
    var productId = null;
    var variationId = null;
    var quantity = 1;
    var variation = {};  // optional nested structure some handlers read
    var attributes = {}; // build attributes[attribute_*] pairs too

    // Serialize form fields first (captures hidden fields)
    var arr = $form.serializeArray();
    arr.forEach(function (item) {
      if (item.name === 'product_id' || item.name === 'add-to-cart') {
        productId = item.value;
      } else if (item.name === 'variation_id') {
        variationId = item.value;
      } else if (item.name === 'quantity' || item.name === 'qty') {
        quantity = item.value;
      } else if (item.name && item.name.indexOf('attribute_') === 0) {
        data[item.name] = item.value;
        variation[item.name] = item.value;
        attributes[item.name] = item.value;
      }
    });

    // Ensure attributes include selects/inputs that might not be serialized if disabled
    $form.find('select[name^="attribute_"], input[name^="attribute_"]').each(function () {
      var $el = $(this);
      var name = this.name;
      var canonical = $el.attr('data-attribute_name') || name; // Woo sets data-attribute_name to canonical key
      var val = $el.val();

      function setAttr(key, value) {
        if (!key) return;
        if (typeof data[key] === 'undefined') data[key] = value || '';
        if (typeof variation[key] === 'undefined') variation[key] = value || '';
        if (typeof attributes[key] === 'undefined') attributes[key] = value || '';
      }

      // Prefer canonical key but also keep the original
      setAttr(canonical, val);
      if (canonical !== name) setAttr(name, val);
    });

    // Build request payload
    data.product_id = productId || $form.find('input[name="product_id"]').val() || '';
    data.variation_id = variationId || $form.find('input[name="variation_id"]').val() || '';
    data.quantity = quantity || 1;
    data['add-to-cart'] = data.product_id; // compatibility with some handlers
    data.variation = variation;

    // Add nested attributes[] pairs
    Object.keys(attributes).forEach(function (key) {
      data['attributes[' + key + ']'] = attributes[key];
    });

    return data;
  }

  function ajaxAddToCart($form) {
    var $button = $form.find('.single_add_to_cart_button');
    var payload = collectVariationFormData($form);
    var isLoopContext = $form.closest('.rs-advanced-add-to-cart').hasClass('rs-context-loop');
    var prevCartRedirect = null;

    if (!payload.variation_id) {
      // Ask Woo to re-check and show messages
      $form.trigger('check_variations');
      return;
    }
    if (!payload.product_id) {
      // Try to fallback to hidden add-to-cart field
      var fallback = $form.find('input[name="add-to-cart"]').val();
      if (fallback) payload.product_id = fallback;
    }

    // For Woo AJAX add_to_cart with variations, pass the variation ID as product_id
    // to ensure the correct item is added to the cart.
    payload.product_id = payload.variation_id;
    delete payload.variation_id;

    // Button/UI state
    $button.addClass('loading').prop('disabled', true);
    // In loop context, suppress downstream Woo/theme redirects driven by this flag
    if (typeof wc_add_to_cart_params !== 'undefined') {
      prevCartRedirect = wc_add_to_cart_params.cart_redirect_after_add;
      wc_add_to_cart_params.cart_redirect_after_add = 'no';
    }
    // Only notify global listeners outside loop context (prevents 3rd-party redirects)
    if (!isLoopContext) {
      $(document.body).trigger('adding_to_cart', [$button, payload]);
    }

    $.ajax({
      url: getAjaxEndpoint('add_to_cart'),
      type: 'POST',
      data: payload,
      dataType: 'json'
    }).done(function (response) {
      if (!response) return;

      // Do not redirect in our handler; always stay on the current page and update fragments.
      // This overrides Woo's cart_redirect_after_add and product_url redirects for this flow.

      // Update fragments
      if (response.fragments) {
        $.each(response.fragments, function (key, value) {
          $(key).replaceWith(value);
        });
      }

      // Notify global listeners so slide carts (e.g., FunnelKit) can react without redirecting
      $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
      jQuery("body").trigger("fkcart_open");


      // Visual state
      $button.removeClass('loading').addClass('added').prop('disabled', false);

      setTimeout(function () {
        $button.removeClass('added');
      }, 10000);

    }).fail(function () {
      // On failure, just re-enable
      $button.removeClass('loading').prop('disabled', false);
    }).always(function () {
      // Restore Woo redirect flag after our flow completes
      if (typeof wc_add_to_cart_params !== 'undefined' && prevCartRedirect !== null) {
        setTimeout(function () { wc_add_to_cart_params.cart_redirect_after_add = prevCartRedirect; }, 0);
      }
    });
  }

  // Hard fallback: intercept programmatic form.submit() used by some themes/plugins
  (function patchFormSubmit() {
    var proto = window.HTMLFormElement && window.HTMLFormElement.prototype;
    if (!proto || proto.__rsPatchedSubmit) return;
    var orig = proto.submit;
    if (typeof orig !== 'function') return;
    proto.__rsPatchedSubmit = true;
    proto.submit = function () {
      try {
        var form = this;
        if (form && form.closest) {
          var wrapper = form.closest('.rs-advanced-add-to-cart.rs-context-loop');
          if (wrapper && (form.querySelector('input[name="variation_id"], select[name^="attribute_"]'))) {
            var $form = $(form);
            var variationId = $form.find('input[name="variation_id"]').val();
            if (!variationId) { $form.trigger('check_variations'); return; }
            ajaxAddToCart($form);
            return; // prevent native submit
          }
        }
      } catch (e) { }
      return orig.apply(this, arguments);
    };
  })();

  // Native capture-phase safety net: prevent legacy submit/click and force AJAX in loop variable forms
  document.addEventListener('submit', function (ev) {
    var form = ev.target;
    if (!form || !form.closest) return;
    var wrapper = form.closest('.rs-advanced-add-to-cart.rs-context-loop');
    if (!wrapper) return;
    // Only handle variable forms
    if (!form.querySelector('input[name="variation_id"], select[name^="attribute_"]')) return;
    ev.preventDefault();
    ev.stopPropagation();
    if (typeof ev.stopImmediatePropagation === 'function') ev.stopImmediatePropagation();
    var $form = $(form);
    var variationId = $form.find('input[name="variation_id"]').val();
    if (!variationId) { $form.trigger('check_variations'); return false; }
    ajaxAddToCart($form);
    return false;
  }, true);

  document.addEventListener('click', function (ev) {
    var target = ev.target;
    if (!target || !target.closest) return;
    var btn = target.closest('.rs-advanced-add-to-cart.rs-context-loop .single_add_to_cart_button');
    if (!btn) return;
    var wrapper = btn.closest('.rs-advanced-add-to-cart.rs-context-loop');
    var form = btn.closest('form') || (wrapper ? wrapper.querySelector('form') : null);
    if (!form) return;
    // Only variable forms
    if (!form.querySelector('input[name="variation_id"], select[name^="attribute_"]')) return;
    ev.preventDefault();
    ev.stopPropagation();
    if (typeof ev.stopImmediatePropagation === 'function') ev.stopImmediatePropagation();
    var $form = $(form);
    var variationId = $form.find('input[name="variation_id"]').val();
    if (!variationId) { $form.trigger('check_variations'); return false; }
    ajaxAddToCart($form);
    return false;
  }, true);

  // Support Elementor live preview where markup may be re-rendered
  if (window.elementorFrontend && window.elementorFrontend.hooks) {
    window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function ($scope) {
      // Ensure Woo's variation JS is initialized for forms rendered by Elementor dynamically
      var $forms = $scope.find('.rs-advanced-add-to-cart form.variations_form');
      if ($forms.length && $.fn.wc_variation_form) {
        $forms.each(function () { $(this).wc_variation_form(); });
      }
      // Prevent native submit by changing button type for loop variable forms in dynamically rendered scopes
      $scope.find('.rs-advanced-add-to-cart.rs-context-loop form.variations_form .single_add_to_cart_button').attr('type', 'button');
    });
  }

})(jQuery);
