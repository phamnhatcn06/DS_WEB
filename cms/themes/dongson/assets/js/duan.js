/**
 * duan.js — Lọc danh sách dự án theo danh mục.
 *
 * Nguồn danh mục là URL param `?category=<slug>`. Ví dụ:
 *   duan.html                          → hiển thị tất cả dự án
 *   duan.html?category=ha-tang-bot     → chỉ hiển thị dự án thuộc "Hạ tầng & BOT"
 *
 * Trang lọc phía client (ẩn/hiện thẻ) và cập nhật URL bằng history API
 * để không phải tải lại trang khi bấm chip lọc.
 */
(function () {
  'use strict';

  var ALL = 'all';

  var grid = document.querySelector('[data-duan-grid]');
  var filter = document.querySelector('[data-duan-filter]');
  if (!grid || !filter) return;

  var chips = Array.prototype.slice.call(filter.querySelectorAll('.duan-chip'));
  var cards = Array.prototype.slice.call(grid.querySelectorAll('[data-category]'));
  var heading = document.getElementById('duan-heading');
  var countEl = document.querySelector('[data-duan-count]');
  var emptyEl = document.querySelector('[data-duan-empty]');

  /** Lấy slug danh mục hiện tại từ URL (mặc định "all"). */
  function getCategoryFromUrl() {
    var params = new URLSearchParams(window.location.search);
    return params.get('category') || ALL;
  }

  /** Nhãn hiển thị của một slug — lấy từ chip tương ứng. */
  function labelForCategory(slug) {
    var chip = chips.find(function (c) { return c.dataset.category === slug; });
    return chip ? chip.textContent.trim() : 'Tất cả dự án';
  }

  /** Áp dụng bộ lọc theo slug: ẩn/hiện thẻ, cập nhật chip active, tiêu đề, số lượng. */
  function applyFilter(slug) {
    var known = chips.some(function (c) { return c.dataset.category === slug; });
    var active = known ? slug : ALL;
    var visible = 0;

    cards.forEach(function (card) {
      var match = active === ALL || card.dataset.category === active;
      card.hidden = !match;
      if (match) visible += 1;
    });

    chips.forEach(function (chip) {
      chip.classList.toggle('is-active', chip.dataset.category === active);
    });

    if (heading) {
      heading.textContent = active === ALL ? 'Tất cả dự án' : labelForCategory(active);
    }
    if (countEl) {
      countEl.textContent = visible + ' dự án';
    }
    if (emptyEl) {
      emptyEl.hidden = visible !== 0;
    }
  }

  /** Cập nhật URL (không tải lại trang) khi đổi danh mục. */
  function pushCategory(slug) {
    var url = slug === ALL ? 'duan.html' : 'duan.html?category=' + encodeURIComponent(slug);
    window.history.pushState({ category: slug }, '', url);
  }

  filter.addEventListener('click', function (event) {
    var chip = event.target.closest('.duan-chip');
    if (!chip) return;
    event.preventDefault();
    var slug = chip.dataset.category || ALL;
    pushCategory(slug);
    applyFilter(slug);
  });

  // Hỗ trợ nút Back/Forward của trình duyệt.
  window.addEventListener('popstate', function () {
    applyFilter(getCategoryFromUrl());
  });

  // Khởi tạo theo URL hiện tại.
  applyFilter(getCategoryFromUrl());
})();
