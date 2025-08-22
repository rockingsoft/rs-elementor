(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var roots = document.querySelectorAll('.rs-adv-images');
    roots.forEach(function (root) {
      var thumbs = Array.prototype.slice.call(root.querySelectorAll('.rs-adv-thumb'));
      if (!thumbs.length) return;
      var mainImg = root.querySelector('.rs-adv-main-img');
      var mainArea = root.querySelector('.rs-adv-main');
      var thumbsWrap = root.querySelector('.rs-adv-thumbs-wrap');
      var thumbsContainer = root.querySelector('.rs-adv-thumbs');
      var thumbsPrev = root.querySelector('.rs-adv-thumbs-prev');
      var thumbsNext = root.querySelector('.rs-adv-thumbs-next');
      var modal = root.querySelector('.rs-adv-modal');
      var modalImg = root.querySelector('.rs-adv-modal-img');
      var btnClose = root.querySelector('.rs-adv-modal-close');
      var btnPrev = root.querySelector('.rs-adv-prev');
      var btnNext = root.querySelector('.rs-adv-next');
      // Inline navigation buttons (outside image)
      var inlinePrev = root.querySelector('.rs-adv-inline-prev');
      var inlineNext = root.querySelector('.rs-adv-inline-next');

      // Variation map: variation_id -> image index
      var varMap = {};
      try {
        var data = root.getAttribute('data-variation-map');
        if (data) { varMap = JSON.parse(data) || {}; }
      } catch (e) { /* noop */ }

      var current = 0;


      // Click behavior configuration
      var clickAction = (root.getAttribute('data-click-action') || 'modal');
      var clickUrl = (root.getAttribute('data-click-url') || '').trim();
      var clickNewTab = root.getAttribute('data-click-new-tab') === '1';
      var mainLink = root.querySelector('.rs-adv-main .rs-adv-main-link');
      function handleNavigate() {
        if (!clickUrl) { openModal(current); return; }
        if (clickNewTab) { window.open(clickUrl, '_blank', 'noopener'); }
        else { window.location.href = clickUrl; }
      }
      function setCurrent(index) {
        if (index < 0 || index >= thumbs.length) return;
        current = index;
        thumbs.forEach(function (t) { t.classList.remove('is-active'); });
        var active = thumbs[index];
        active.classList.add('is-active');

        // If single-line mode is active, scroll the thumbnail into view.
        // Always ensure active thumb is visible
        active.scrollIntoView({
          behavior: 'smooth',
          block: 'nearest',
          inline: 'center'
        });
        var large = active.getAttribute('data-large') || active.getAttribute('data-full');
        if (large && mainImg) { mainImg.src = large; }
        // Aspect ratio will update on image load event
        updateNavVisibility();
        updateThumbNav();
      }

      function updateModalImage() {
        var target = thumbs[current];
        if (!target || !modalImg) return;
        var full = target.getAttribute('data-full') || target.getAttribute('data-large');
        if (full) { modalImg.src = full; }
      }

      function updateNavVisibility() {
        var atStart = current <= 0;
        var atEnd = current >= (thumbs.length - 1);
        // Modal prev/next: hide at ends
        if (btnPrev && btnNext) {
          btnPrev.style.display = atStart ? 'none' : '';
          btnPrev.setAttribute('aria-hidden', atStart ? 'true' : 'false');
          btnPrev.setAttribute('aria-disabled', atStart ? 'true' : 'false');
          btnPrev.tabIndex = atStart ? -1 : 0;
          btnNext.style.display = atEnd ? 'none' : '';
          btnNext.setAttribute('aria-hidden', atEnd ? 'true' : 'false');
          btnNext.setAttribute('aria-disabled', atEnd ? 'true' : 'false');
          btnNext.tabIndex = atEnd ? -1 : 0;
        }
        // Inline prev/next: keep visible but disabled at ends
        if (inlinePrev) {
          inlinePrev.classList.toggle('is-disabled', atStart);
          inlinePrev.setAttribute('aria-disabled', atStart ? 'true' : 'false');
          inlinePrev.tabIndex = atStart ? -1 : 0;
        }
        if (inlineNext) {
          inlineNext.classList.toggle('is-disabled', atEnd);
          inlineNext.setAttribute('aria-disabled', atEnd ? 'true' : 'false');
          inlineNext.tabIndex = atEnd ? -1 : 0;
        }
      }

      function openModal(index) {
        if (!modal) return;
        if (index < 0 || index >= thumbs.length) return;
        current = index;
        updateModalImage();
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        updateNavVisibility();
      }

      function closeModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }

      thumbs.forEach(function (btn, idx) {
        btn.addEventListener('click', function () {
          if (idx === current) {
            if (clickAction === 'link') {
              if (mainLink) { mainLink.click(); }
              else { handleNavigate(); }
            } else {
              openModal(current);
            }
          } else {
            setCurrent(idx);
          }
        });
      });

      if (mainArea) {
        // In link mode with an anchor, let the anchor handle interactions natively
        if (!(clickAction === 'link' && mainLink)) {
          mainArea.addEventListener('click', function () {
            if (clickAction === 'link') { handleNavigate(); }
            else { openModal(current); }
          });
          mainArea.addEventListener('keypress', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
              e.preventDefault();
              if (clickAction === 'link') { handleNavigate(); }
              else { openModal(current); }
            }
          });
        }
      }

      if (btnClose) btnClose.addEventListener('click', closeModal);
      if (modal) {
        modal.addEventListener('click', function (e) {
          if (e.target.classList.contains('rs-adv-modal-backdrop')) closeModal();
        });
      }

      function showPrev() { if (current > 0) { setCurrent(current - 1); updateModalImage(); updateNavVisibility(); } }
      function showNext() { if (current < thumbs.length - 1) { setCurrent(current + 1); updateModalImage(); updateNavVisibility(); } }
      if (btnPrev) btnPrev.addEventListener('click', showPrev);
      if (btnNext) btnNext.addEventListener('click', showNext);

      // Inline buttons handlers
      if (inlinePrev) inlinePrev.addEventListener('click', function () {
        if (inlinePrev.classList.contains('is-disabled')) return;
        showPrev();
      });
      if (inlineNext) inlineNext.addEventListener('click', function () {
        if (inlineNext.classList.contains('is-disabled')) return;
        showNext();
      });

      window.addEventListener('keydown', function (e) {
        if (!modal || !modal.classList.contains('is-open')) return;
        if (e.key === 'Escape') { closeModal(); }
        if (e.key === 'ArrowLeft') { showPrev(); }
        if (e.key === 'ArrowRight') { showNext(); }
      });

      var content = root.querySelector('.rs-adv-modal-content');
      if (modal) {
        modal.addEventListener('click', function (e) {
          if (e.target.classList.contains('rs-adv-modal-backdrop')) { closeModal(); }
        });
      }
      if (content && modal) {
        content.addEventListener('click', function (e) { if (e.target === content) { closeModal(); } });
      }

      if (modalImg) {
        modalImg.addEventListener('click', function (e) {
          var rect = modalImg.getBoundingClientRect();
          var x = e.clientX - rect.left;
          if (x < rect.width / 2) { showPrev(); } else { showNext(); }
        });
      }

      setCurrent(0);
      updateNavVisibility();

      // ---- Thumbnails nav (arrows) ----
      function isVertical() {
        if (!thumbsContainer) return false;
        var style = window.getComputedStyle(thumbsContainer);
        return style.flexDirection === 'column';
      }

      function updateThumbNav() {
        if (!thumbsContainer || !thumbsPrev || !thumbsNext) return;
        var vertical = isVertical();
        var pos = vertical ? thumbsContainer.scrollTop : thumbsContainer.scrollLeft;
        var size = vertical ? thumbsContainer.clientHeight : thumbsContainer.clientWidth;
        var scrollSize = vertical ? thumbsContainer.scrollHeight : thumbsContainer.scrollWidth;

        // If content fits entirely, hide both buttons
        var scrollable = scrollSize > size + 1;
        thumbsPrev.classList.toggle('is-hidden', !scrollable);
        thumbsNext.classList.toggle('is-hidden', !scrollable);
        if (!scrollable) return;

        var atStart = pos <= 0;
        var atEnd = (pos + size) >= (scrollSize - 1);
        thumbsPrev.classList.toggle('is-disabled', atStart);
        thumbsPrev.setAttribute('aria-disabled', atStart ? 'true' : 'false');
        thumbsPrev.tabIndex = atStart ? -1 : 0;
        thumbsNext.classList.toggle('is-disabled', atEnd);
        thumbsNext.setAttribute('aria-disabled', atEnd ? 'true' : 'false');
        thumbsNext.tabIndex = atEnd ? -1 : 0;
      }

      function scrollThumbs(dir) {
        if (!thumbsContainer) return;
        var vertical = isVertical();
        var gap = parseInt(getComputedStyle(thumbsContainer).gap || '8', 10) || 8;
        // Use first thumb size as step
        var thumbEl = thumbs[0];
        var step = 0;
        if (thumbEl) {
          var rect = thumbEl.getBoundingClientRect();
          step = vertical ? (rect.height + gap) : (rect.width + gap);
        }
        if (!step) step = vertical ? (thumbsContainer.clientHeight * 0.9) : (thumbsContainer.clientWidth * 0.9);
        var delta = dir * step;
        if (vertical) {
          thumbsContainer.scrollBy({ top: delta, left: 0, behavior: 'smooth' });
        } else {
          thumbsContainer.scrollBy({ left: delta, top: 0, behavior: 'smooth' });
        }
        // Recompute disabled state after scrolling settles
        setTimeout(updateThumbNav, 250);
      }

      if (thumbsPrev && thumbsNext && thumbsContainer) {
        thumbsPrev.addEventListener('click', function () {
          if (thumbsPrev.classList.contains('is-disabled')) return;
          scrollThumbs(-1);
        });
        thumbsNext.addEventListener('click', function () {
          if (thumbsNext.classList.contains('is-disabled')) return;
          scrollThumbs(1);
        });
        thumbsContainer.addEventListener('scroll', updateThumbNav, { passive: true });
        window.addEventListener('resize', function () { setTimeout(updateThumbNav, 100); });
        // Initialize state
        updateThumbNav();
      }

      // Editor preview: if modal has preview-open class, open it programmatically
      if (modal && modal.classList.contains('is-preview-open')) {
        openModal(current);
      }

      // --- Variation sync via events ---
      function handleVariationSelection(variationId) {
        if (!variationId) { setCurrent(0); updateModalImage(); updateNavVisibility(); return; }
        var key = String(variationId);
        if (Object.prototype.hasOwnProperty.call(varMap, key)) {
          var idx = parseInt(varMap[key], 10);
          if (!isNaN(idx)) { setCurrent(idx); updateModalImage(); updateNavVisibility(); }
        }
      }

      // Listen to our custom chooser event on document (native and jQuery)
      document.addEventListener('rs_varc_change', function (e) {
        if (e && e.detail && typeof e.detail.variationId !== 'undefined') {
          handleVariationSelection(e.detail.variationId);
        }
      });
      if (window.jQuery) {
        window.jQuery(document).on('rs_varc_change', function (_e, variationId) {
          handleVariationSelection(variationId);
        });
      }

      // Also bind to nearest variations_form for Woo events
      var form = root.closest('.product') || root.parentElement;
      if (form) { form = form.querySelector('.variations_form') || form.closest('.variations_form') || form; }
      if (form && form.addEventListener) {
        form.addEventListener('found_variation', function (ev) {
          try {
            var variation = ev.detail && ev.detail.variation ? ev.detail.variation : (ev.originalEvent && ev.originalEvent.detail && ev.originalEvent.detail.variation);
            var vid = variation && (variation.variation_id || variation.variationId);
            handleVariationSelection(vid);
          } catch (_) { }
        });
        form.addEventListener('reset_data', function () { handleVariationSelection(null); });
        form.addEventListener('woocommerce_variation_has_changed', function () {
          // If Woo cleared selection without found_variation, fallback to first.
          var selects = form.querySelectorAll('select[name^="attribute_"]');
          var anyEmpty = false; selects.forEach(function (s) { if (!s.value) anyEmpty = true; });
          if (anyEmpty) handleVariationSelection(null);
        });
      }
      if (window.jQuery && form) {
        var $form = window.jQuery(form);
        $form.on('found_variation', function (_e, variation) {
          var vid = variation && (variation.variation_id || variation.variationId);
          handleVariationSelection(vid);
        });
        $form.on('reset_data', function () { handleVariationSelection(null); });
        $form.on('woocommerce_variation_has_changed', function () {
          var anyEmpty = false; $form.find('select[name^="attribute_"]').each(function () { if (!this.value) anyEmpty = true; });
          if (anyEmpty) handleVariationSelection(null);
        });
      }
    });
  });
})();
