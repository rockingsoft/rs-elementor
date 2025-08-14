(function(){
  document.addEventListener('DOMContentLoaded', function(){
    var roots = document.querySelectorAll('.rs-adv-images');
    roots.forEach(function(root){
      var thumbs = Array.prototype.slice.call(root.querySelectorAll('.rs-adv-thumb'));
      if (!thumbs.length) return;
      var mainImg = root.querySelector('.rs-adv-main-img');
      var mainArea = root.querySelector('.rs-adv-main');
      var modal = root.querySelector('.rs-adv-modal');
      var modalImg = root.querySelector('.rs-adv-modal-img');
      var btnClose = root.querySelector('.rs-adv-modal-close');
      var btnPrev = root.querySelector('.rs-adv-prev');
      var btnNext = root.querySelector('.rs-adv-next');

      var current = 0;
      function setCurrent(index){
        if (index < 0 || index >= thumbs.length) return;
        current = index;
        thumbs.forEach(function(t){ t.classList.remove('is-active'); });
        var active = thumbs[index];
        active.classList.add('is-active');
        var large = active.getAttribute('data-large') || active.getAttribute('data-full');
        if (large && mainImg) { mainImg.src = large; }
        if (modal && modal.classList.contains('is-open')) updateNavVisibility();
      }

      function updateModalImage(){
        var target = thumbs[current];
        if (!target || !modalImg) return;
        var full = target.getAttribute('data-full') || target.getAttribute('data-large');
        if (full) { modalImg.src = full; }
      }

      function updateNavVisibility(){
        if (!btnPrev || !btnNext) return;
        var atStart = current <= 0;
        var atEnd = current >= (thumbs.length - 1);
        btnPrev.style.display = atStart ? 'none' : '';
        btnPrev.setAttribute('aria-hidden', atStart ? 'true' : 'false');
        btnPrev.setAttribute('aria-disabled', atStart ? 'true' : 'false');
        btnPrev.tabIndex = atStart ? -1 : 0;
        btnNext.style.display = atEnd ? 'none' : '';
        btnNext.setAttribute('aria-hidden', atEnd ? 'true' : 'false');
        btnNext.setAttribute('aria-disabled', atEnd ? 'true' : 'false');
        btnNext.tabIndex = atEnd ? -1 : 0;
      }

      function openModal(index){
        if (!modal) return;
        if (index < 0 || index >= thumbs.length) return;
        current = index;
        updateModalImage();
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        updateNavVisibility();
      }

      function closeModal(){
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
      }

      thumbs.forEach(function(btn, idx){
        btn.addEventListener('click', function(){
          if (idx === current) {
            openModal(current);
          } else {
            setCurrent(idx);
          }
        });
      });

      if (mainArea) {
        mainArea.addEventListener('click', function(){ openModal(current); });
        mainArea.addEventListener('keypress', function(e){
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            openModal(current);
          }
        });
      }

      if (btnClose) btnClose.addEventListener('click', closeModal);
      if (modal) {
        modal.addEventListener('click', function(e){
          if (e.target.classList.contains('rs-adv-modal-backdrop')) closeModal();
        });
      }

      function showPrev(){ if (current > 0) { setCurrent(current - 1); updateModalImage(); updateNavVisibility(); } }
      function showNext(){ if (current < thumbs.length - 1) { setCurrent(current + 1); updateModalImage(); updateNavVisibility(); } }
      if (btnPrev) btnPrev.addEventListener('click', showPrev);
      if (btnNext) btnNext.addEventListener('click', showNext);

      window.addEventListener('keydown', function(e){
        if (!modal || !modal.classList.contains('is-open')) return;
        if (e.key === 'Escape') { closeModal(); }
        if (e.key === 'ArrowLeft') { showPrev(); }
        if (e.key === 'ArrowRight') { showNext(); }
      });

      var content = root.querySelector('.rs-adv-modal-content');
      if (modal) {
        modal.addEventListener('click', function(e){
          if (e.target.classList.contains('rs-adv-modal-backdrop')) { closeModal(); }
        });
      }
      if (content && modal) {
        content.addEventListener('click', function(e){ if (e.target === content) { closeModal(); } });
      }

      if (modalImg) {
        modalImg.addEventListener('click', function(e){
          var rect = modalImg.getBoundingClientRect();
          var x = e.clientX - rect.left;
          if (x < rect.width / 2) { showPrev(); } else { showNext(); }
        });
      }

      setCurrent(0);
      updateNavVisibility();
    });
  });
})();
