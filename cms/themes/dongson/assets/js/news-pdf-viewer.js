/**
 * Xem PDF trong popup ở trang chi tiết tin tức.
 *
 * Biên tập viên chèn link tệp PDF vào thân bài qua TinyMCE. Thay vì rời trang,
 * các link .pdf trong .nd-body sẽ mở trong modal Bootstrap có iframe xem trước,
 * kèm nút tải xuống và mở tab mới. Không phụ thuộc thư viện ngoài (Bootstrap
 * bundle đã nạp ở layout).
 */
(function () {
  'use strict';

  // Nhận diện link trỏ tới file PDF (bỏ qua query string / fragment).
  function isPdfLink(anchor) {
    var href = anchor.getAttribute('href') || '';
    return /\.pdf(\?|#|$)/i.test(href);
  }

  function init() {
    var body = document.querySelector('.nd-body');
    var modalEl = document.getElementById('pdfViewerModal');
    if (!body || !modalEl || typeof bootstrap === 'undefined') {
      return;
    }

    var modal = new bootstrap.Modal(modalEl);
    var frame = modalEl.querySelector('[data-pdf-frame]');
    var titleEl = modalEl.querySelector('[data-pdf-title]');
    var downloadLink = modalEl.querySelector('[data-pdf-download]');
    var openLink = modalEl.querySelector('[data-pdf-open]');

    // Uỷ quyền sự kiện: bắt click trên mọi link PDF trong thân bài.
    body.addEventListener('click', function (event) {
      var anchor = event.target.closest ? event.target.closest('a[href]') : null;
      if (!anchor || !body.contains(anchor) || !isPdfLink(anchor)) {
        return;
      }
      event.preventDefault();

      var url = anchor.href;
      var label = (anchor.textContent || '').trim() || 'Tài liệu PDF';

      titleEl.textContent = label;
      downloadLink.setAttribute('href', url);
      openLink.setAttribute('href', url);
      frame.setAttribute('src', url);
      modal.show();
    });

    // Ngừng tải PDF khi đóng popup để không chạy ngầm.
    modalEl.addEventListener('hidden.bs.modal', function () {
      frame.setAttribute('src', 'about:blank');
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
