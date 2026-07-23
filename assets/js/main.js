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

  document.addEventListener('DOMContentLoaded', function () {
    initFadeOnScroll();
    initHeaderGlass();
    initCustomSliders();
  });
})();
