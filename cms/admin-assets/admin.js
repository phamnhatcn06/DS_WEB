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

  /**
   * Bộ chọn ảnh: mỗi trường media có nút mở modal thư viện dùng chung.
   * Modal cho phép tải ảnh mới lên (AJAX) hoặc chọn ảnh có sẵn.
   */
  function initMediaPicker() {
    var modalEl = document.getElementById('mediaPickerModal');
    if (!modalEl || typeof bootstrap === 'undefined') {
      return;
    }

    var modal = new bootstrap.Modal(modalEl);
    var grid = modalEl.querySelector('[data-media-grid]');
    var status = modalEl.querySelector('[data-media-status]');
    var searchInput = modalEl.querySelector('[data-media-search]');
    var fileInput = modalEl.querySelector('[data-media-file]');
    var listUrl = modalEl.getAttribute('data-list-url');
    var uploadUrl = modalEl.getAttribute('data-upload-url');
    var csrfName = modalEl.getAttribute('data-csrf-name');
    var csrfValue = modalEl.getAttribute('data-csrf-value');

    var activeField = null; // trường media đang được chọn ảnh
    var pickCallback = null; // callback khi mở picker ở chế độ "chọn ảnh trả về" (vd TinyMCE)
    var searchTimer = null;
    var currentScope = 'image'; // image | doc | all — loại file được liệt kê/tải lên
    var defaultAccept = fileInput ? fileInput.getAttribute('accept') : 'image/*';

    // Chọn xong một ảnh: nếu đang ở chế độ callback thì trả ảnh về nơi gọi,
    // ngược lại gán vào trường media đang mở.
    function finishPick(item) {
      if (pickCallback) {
        var cb = pickCallback;
        pickCallback = null;
        modal.hide();
        cb(item);
      } else {
        applyToField(item);
      }
    }

    function setStatus(text, isError) {
      status.textContent = text || '';
      status.classList.toggle('is-error', !!isError);
    }

    function applyToField(item) {
      if (!activeField) {
        return;
      }
      activeField.querySelector('[data-media-input]').value = item.id;
      var img = activeField.querySelector('[data-media-img]');
      img.src = item.url;
      img.hidden = false;
      activeField.querySelector('[data-media-empty]').hidden = true;
      activeField.querySelector('[data-media-box]').classList.remove('is-empty');
      activeField.querySelector('[data-media-name]').textContent = item.name;
      activeField.querySelector('[data-media-clear]').hidden = false;
    }

    // Chọn icon FontAwesome theo loại tệp (dùng cho item không phải ảnh).
    function fileIconClass(item) {
      var mime = item.mime || '';
      if (mime === 'application/pdf' || /\.pdf$/i.test(item.name)) {
        return 'fa-file-pdf-o';
      }
      return 'fa-file-o';
    }

    function renderItems(items) {
      grid.innerHTML = '';
      if (!items.length) {
        grid.innerHTML = '<p class="text-muted m-0 p-3">Chưa có tệp nào phù hợp.</p>';
        return;
      }
      var selectedId = activeField
        ? activeField.querySelector('[data-media-input]').value
        : '';
      items.forEach(function (item) {
        var cell = document.createElement('button');
        cell.type = 'button';
        cell.className = 'media-picker-item';
        if (String(item.id) === String(selectedId)) {
          cell.classList.add('is-selected');
        }
        // Ảnh hiện thumbnail; tệp khác (PDF…) hiện icon theo phần mở rộng.
        var thumb = item.isImage
          ? '<img src="' + item.url + '" alt="" loading="lazy" />'
          : '<span class="media-picker-item-icon"><i class="fa ' + fileIconClass(item) + '"></i>'
            + (item.size ? '<small>' + item.size + '</small>' : '') + '</span>';
        cell.innerHTML =
          thumb +
          '<span class="media-picker-item-name">' + item.name + '</span>';
        cell.addEventListener('click', function () {
          finishPick(item);
          modal.hide();
        });
        grid.appendChild(cell);
      });
    }

    function loadList(query) {
      setStatus('Đang tải…');
      var url = listUrl + (listUrl.indexOf('?') === -1 ? '?' : '&') +
        'q=' + encodeURIComponent(query || '') +
        '&scope=' + encodeURIComponent(currentScope);
      fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          renderItems(data.items || []);
          setStatus('');
        })
        .catch(function () {
          setStatus('Không tải được danh sách ảnh.', true);
        });
    }

    function uploadFiles(files) {
      if (!files.length) {
        return;
      }
      var remaining = files.length;
      var lastItem = null;
      setStatus('Đang tải lên ' + remaining + ' ảnh…');

      Array.prototype.forEach.call(files, function (file) {
        var formData = new FormData();
        formData.append('file', file);
        formData.append(csrfName, csrfValue);

        fetch(uploadUrl, {
          method: 'POST',
          body: formData,
          headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
          .then(function (res) { return res.json(); })
          .then(function (data) {
            if (data.success) {
              lastItem = data.item;
            } else {
              setStatus(data.message || 'Tải lên thất bại.', true);
            }
          })
          .catch(function () {
            setStatus('Lỗi kết nối khi tải lên.', true);
          })
          .then(function () {
            remaining--;
            if (remaining === 0) {
              if (lastItem) {
                finishPick(lastItem);
                setStatus('Đã tải lên xong.');
              }
              loadList(searchInput.value);
            }
          });
      });
    }

    // Mở modal từ nút của từng trường media.
    document.querySelectorAll('[data-media-open]').forEach(function (button) {
      button.addEventListener('click', function () {
        activeField = button.closest('[data-media-field]');
        setStatus('');
        modal.show();
        loadList(searchInput.value);
      });
    });

    // Nút bỏ chọn ảnh của từng trường.
    document.querySelectorAll('[data-media-clear]').forEach(function (button) {
      button.addEventListener('click', function () {
        var field = button.closest('[data-media-field]');
        field.querySelector('[data-media-input]').value = '';
        var img = field.querySelector('[data-media-img]');
        img.hidden = true;
        img.src = '';
        field.querySelector('[data-media-empty]').hidden = false;
        field.querySelector('[data-media-box]').classList.add('is-empty');
        field.querySelector('[data-media-name]').textContent = '';
        button.hidden = true;
      });
    });

    fileInput.addEventListener('change', function () {
      uploadFiles(fileInput.files);
      fileInput.value = '';
    });

    searchInput.addEventListener('input', function () {
      window.clearTimeout(searchTimer);
      searchTimer = window.setTimeout(function () {
        loadList(searchInput.value);
      }, 300);
    });

    // Đóng modal mà chưa chọn → huỷ chế độ callback để không trả ảnh nhầm lần sau.
    modalEl.addEventListener('hidden.bs.modal', function () {
      pickCallback = null;
    });

    // API dùng chung: mở thư viện ảnh, trả ảnh đã chọn về callback (vd cho TinyMCE).
    // callback nhận { id, url, name }.
    window.DSHMediaPicker = {
      open: function (callback) {
        pickCallback = callback;
        activeField = null;
        setStatus('');
        modal.show();
        loadList('');
      }
    };
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
    initMediaPicker();
    initSubmitLock();
  });
})();
