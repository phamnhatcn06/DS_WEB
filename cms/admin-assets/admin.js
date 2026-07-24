/**
 * Tương tác nhỏ của giao diện quản trị.
 */
(function () {
  'use strict';

  /** Mở/đóng sidebar trên màn hình nhỏ. */
  function initSidebarToggle() {
    var toggle = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('adminSidebar');
    if (!toggle || !sidebar) {
      return;
    }
    toggle.addEventListener('click', function () {
      sidebar.classList.toggle('is-open');
    });
  }

  /**
   * Hỏi xác nhận trước khi submit form xoá.
   * Form xoá luôn là POST — không xoá được bằng link GET.
   */
  function initDeleteConfirm() {
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!window.confirm(form.getAttribute('data-confirm'))) {
          event.preventDefault();
        }
      });
    });
  }

  /** Xem trước ảnh khi chọn trong dropdown media. */
  function initMediaPreview() {
    document.querySelectorAll('select[data-media-select]').forEach(function (select) {
      var preview = document.querySelector(
        '[data-media-preview="' + select.getAttribute('data-media-select') + '"]'
      );
      if (!preview) {
        return;
      }
      select.addEventListener('change', function () {
        var url = select.options[select.selectedIndex].getAttribute('data-url');
        if (url) {
          preview.src = url;
          preview.hidden = false;
        } else {
          preview.hidden = true;
        }
      });
    });
  }

  /** Chặn double-submit: khoá nút ngay khi form được gửi. */
  function initSubmitLock() {
    document.querySelectorAll('form[data-lock-submit]').forEach(function (form) {
      form.addEventListener('submit', function () {
        var button = form.querySelector('button[type="submit"]');
        if (!button || button.disabled) {
          return;
        }
        button.disabled = true;
        button.dataset.originalHtml = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang lưu…';

        // Nếu validate phía server trả lại trang, nút sẽ được render mới —
        // nhưng phòng trường hợp trình duyệt khôi phục từ bfcache thì mở lại.
        window.setTimeout(function () {
          button.disabled = false;
          button.innerHTML = button.dataset.originalHtml;
        }, 10000);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initSidebarToggle();
    initDeleteConfirm();
    initMediaPreview();
    initSubmitLock();
  });
})();
