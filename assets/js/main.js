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

  document.addEventListener('DOMContentLoaded', function () {
    initFadeOnScroll();
  });
})();
