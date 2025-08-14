(function(){
  document.addEventListener('DOMContentLoaded', function(){
    var widgets = document.querySelectorAll('.rs-product-reviews');
    widgets.forEach(function(container){
      var widgetId = container.id;
      if (!widgetId) return;
      var showAllBtn = container.querySelector('.rs-show-all-reviews');
      var modal = document.getElementById(widgetId + '-modal');
      if (!modal) return;
      var closeBtn = modal.querySelector('.rs-reviews-modal-close');
      var modalList = modal.querySelector('.rs-reviews-modal-list');
      var sortToggle = modal.querySelector('.rs-sort-toggle');

      function sortReviews(sortType){
        if (!modalList) return;
        var items = Array.prototype.slice.call(modalList.querySelectorAll('.rs-review-item'));
        if (!items.length) return;
        var desc = sortType === 'rating-desc';
        items.sort(function(a, b){
          var ra = parseFloat(a.getAttribute('data-rating')) || 0;
          var rb = parseFloat(b.getAttribute('data-rating')) || 0;
          return desc ? (rb - ra) : (ra - rb);
        });
        items.forEach(function(el){ modalList.appendChild(el); });
      }

      if (showAllBtn) {
        showAllBtn.addEventListener('click', function(){
          modal.style.display = 'block';
          sortReviews('rating-desc');
          document.body.style.overflow = 'hidden';
        });
      }

      if (closeBtn) {
        closeBtn.addEventListener('click', function(){
          modal.style.display = 'none';
          document.body.style.overflow = '';
        });
      }

      window.addEventListener('click', function(event){
        if (event.target === modal) {
          modal.style.display = 'none';
          document.body.style.overflow = '';
        }
      });

      if (sortToggle) {
        sortToggle.addEventListener('click', function(){
          var current = this.getAttribute('data-sort') || 'rating-desc';
          var next = current === 'rating-desc' ? 'rating-asc' : 'rating-desc';
          this.setAttribute('data-sort', next);
          var icon = this.querySelector('i');
          if (icon) {
            if (next === 'rating-desc') {
              icon.className = 'fas fa-sort-amount-down';
              this.title = 'Highest to Lowest';
            } else {
              icon.className = 'fas fa-sort-amount-up';
              this.title = 'Lowest to Highest';
            }
          }
          sortReviews(next);
        });
      }

      // Ensure initial ordering matches default and correct icon state
      sortReviews('rating-desc');
    });
  });
})();
