/* ===== Đông Sơn Holdings — Quan hệ cổ đông / Báo cáo tài chính ===== */
(function () {
  'use strict';

  // --- Lọc báo cáo theo năm (ẩn/hiện, không reload) ---
  function initYearFilter() {
    const chips = document.querySelectorAll('.year-chip');
    const cards = document.querySelectorAll('.report-card');
    if (!chips.length) return;

    chips.forEach((chip) => {
      chip.addEventListener('click', () => {
        chips.forEach((c) => c.classList.remove('is-active'));
        chip.classList.add('is-active');

        const year = chip.dataset.year;
        cards.forEach((card) => {
          const match = year === 'all' || card.dataset.year === year;
          card.style.display = match ? '' : 'none';
        });
      });
    });
  }

  // --- Fade-in khi cuộn (IntersectionObserver, không poll scroll) ---
  function initFadeIn() {
    const sections = document.querySelectorAll('.fade-section');
    if (!sections.length) return;

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });

    sections.forEach((el) => observer.observe(el));
  }

  document.addEventListener('DOMContentLoaded', () => {
    initYearFilter();
    initFadeIn();
  });
})();
