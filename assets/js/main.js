/* ==========================================================================
   main.js — Tương tác cho trang chủ Đông Sơn Holdings (DSH)
   Sẽ bổ sung dần: carousel config, news filter, header glassmorphism, fade-in.
   ========================================================================== */

(function () {
  'use strict';

  /**
   * Fade-in section khi cuộn vào viewport — dùng IntersectionObserver,
   * không poll sự kiện scroll.
   */
  function initFadeOnScroll() {
    var sections = document.querySelectorAll('.fade-section');
    if (!sections.length || !('IntersectionObserver' in window)) {
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    sections.forEach(function (section) {
      observer.observe(section);
    });
  }

  /**
   * Reveal từng phần tử khi cuộn tới (stagger) — thêm .is-visible cho mỗi
   * phần tử [data-reveal] riêng lẻ. Dùng cho timeline (Section 7) để mỗi mốc
   * hiện dần theo lúc cuộn, không hiện cả khối một lần.
   */
  function initRevealItems() {
    var items = document.querySelectorAll('[data-reveal]');
    if (!items.length || !('IntersectionObserver' in window)) {
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.25, rootMargin: '0px 0px -10% 0px' });

    items.forEach(function (item) {
      observer.observe(item);
    });
  }

  /**
   * Bật/tắt glassmorphism cho header dựa trên vị trí sentinel ở đỉnh trang.
   * Dùng IntersectionObserver — không poll sự kiện scroll.
   */
  function initHeaderGlass() {
    var header = document.getElementById('siteHeader');
    var sentinel = document.querySelector('.header-sentinel');
    if (!header || !sentinel || !('IntersectionObserver' in window)) {
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      // Sentinel rời khỏi viewport => đã cuộn xuống => bật nền kính.
      header.classList.toggle('is-scrolled', !entries[0].isIntersecting);
    }, { threshold: 0 });

    observer.observe(sentinel);
  }

  /**
   * Slider tùy biến dùng chung: đồng bộ counter "0X / 0N" và dash active với
   * Bootstrap Carousel. Áp cho mọi carousel có thuộc tính [data-custom-slider].
   * Bên trong tìm phần tử .js-counter (hiện số) và các nút .dash.
   */
  function initCustomSliders() {
    var sliders = document.querySelectorAll('[data-custom-slider]');

    sliders.forEach(function (carousel) {
      var counter = carousel.querySelector('.js-counter');
      var dashes = carousel.querySelectorAll('.dash');

      carousel.addEventListener('slide.bs.carousel', function (event) {
        var index = event.to;
        if (counter) {
          counter.textContent = String(index + 1).padStart(2, '0');
        }
        dashes.forEach(function (dash, i) {
          dash.classList.toggle('active', i === index);
        });
      });
    });
  }

  /**
   * Slider ảnh cuộn ngang có scroll-snap (Section 5 — Dự án tiêu biểu).
   * Mở đầu căn giữa thẻ ở giữa dải để hai thẻ ngoài cùng bị cắt một nửa;
   * nút prev/next cuộn đi đúng một thẻ.
   */
  function initScrollSliders() {
    var sliders = document.querySelectorAll('[data-duan-slider]');

    sliders.forEach(function (slider) {
      var track = slider.querySelector('.duan-gallery');
      var items = slider.querySelectorAll('.duan-item');
      if (!track || !items.length) {
        return;
      }

      function scrollByOneCard(direction) {
        var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
        track.scrollBy({ left: direction * (items[0].offsetWidth + gap), behavior: 'smooth' });
      }

      function centerMiddleCard() {
        var middle = items[Math.floor(items.length / 2)];
        track.scrollLeft = middle.offsetLeft - (track.clientWidth - middle.offsetWidth) / 2;
      }

      var prevButton = slider.querySelector('[data-duan-prev]');
      var nextButton = slider.querySelector('[data-duan-next]');

      if (prevButton) {
        prevButton.addEventListener('click', function () { scrollByOneCard(-1); });
      }
      if (nextButton) {
        nextButton.addEventListener('click', function () { scrollByOneCard(1); });
      }

      centerMiddleCard();
      window.addEventListener('resize', centerMiddleCard);
    });
  }

  /**
   * Tab lọc tin tức theo danh mục (Section 9). Ẩn/hiện card qua class
   * .is-hidden dựa trên [data-category], không tải lại trang.
   */
  function initNewsFilter() {
    var filterBars = document.querySelectorAll('[data-news-filter]');

    filterBars.forEach(function (bar) {
      var buttons = bar.querySelectorAll('[data-filter]');
      var items = document.querySelectorAll('.news-item');
      var section = bar.closest('.tintuc');

      function applyFilter(category) {
        // Bỏ độ lệch trang trí của cột trái khi lưới chỉ còn một danh mục.
        if (section) {
          section.classList.toggle('is-filtered', category !== 'all');
        }
        items.forEach(function (item) {
          var matched = category === 'all' || item.dataset.category === category;
          item.classList.toggle('is-hidden', !matched);
        });
      }

      buttons.forEach(function (button) {
        button.addEventListener('click', function () {
          buttons.forEach(function (other) {
            var isCurrent = other === button;
            other.classList.toggle('active', isCurrent);
            other.setAttribute('aria-selected', String(isCurrent));
          });
          applyFilter(button.dataset.filter);
        });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initFadeOnScroll();
    initRevealItems();
    initHeaderGlass();
    initCustomSliders();
    initScrollSliders();
    initNewsFilter();
  });
})();
