/**
 * Màn hình soạn bài viết kiểu WordPress:
 *  - Tự sinh slug từ tiêu đề (bỏ dấu tiếng Việt) khi người dùng chưa tự sửa slug.
 *  - Khởi tạo trình soạn thảo TinyMCE (self-host) cho các ô nội dung (VI + EN).
 *  - Bật/tắt khối "Thông tin dự án" và "Quan hệ cổ đông" theo danh mục được chọn.
 *  - Quản lý danh sách file đính kèm (chọn từ thư viện / tải PDF lên).
 *
 * Nạp cuối trang (defer) sau tinymce.min.js và admin.js.
 */
(function () {
  'use strict';

  // ------------------------------------------------------------ tiện ích
  function removeVietnameseTones(str) {
    return str
      .normalize('NFD')
      .replace(/[̀-ͯ]/g, '') // bỏ dấu thanh (combining marks)
      .replace(/đ/g, 'd')
      .replace(/Đ/g, 'D');
  }

  function slugify(text) {
    return removeVietnameseTones(String(text))
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // ------------------------------------------------------------ slug tiếng Việt
  function initSlug() {
    var titleInput = document.querySelector('[data-slug-source]');
    var slugInput = document.querySelector('[data-slug-target]');
    if (!titleInput || !slugInput) {
      return;
    }

    slugInput.addEventListener('input', function () {
      slugInput.dataset.slugEdited = '1';
    });

    titleInput.addEventListener('input', function () {
      if (slugInput.dataset.slugEdited === '1') {
        return;
      }
      slugInput.value = slugify(titleInput.value);
    });
  }

  // ------------------------------------------------------------ TinyMCE editor
  function initEditor() {
    if (typeof window.tinymce === 'undefined') {
      return;
    }
    // Cả ô nội dung tiếng Việt lẫn tiếng Anh đều dùng class .wp-content-editor.
    var editors = document.querySelectorAll('.wp-content-editor');
    if (!editors.length) {
      return;
    }

    window.tinymce.init({
      selector: '.wp-content-editor',
      base_url: window.DSH_TINYMCE_BASE, // đường dẫn self-host, không CDN
      suffix: '.min',
      license_key: 'gpl',
      language: 'vi',
      height: 480,
      menubar: 'edit insert format table',
      plugins: 'advlist lists link image table code fullscreen media autolink wordcount',
      toolbar:
        'undo redo | blocks | bold italic underline | ' +
        'alignleft aligncenter alignright | bullist numlist | ' +
        'link dshmedia dshfile image media table | code fullscreen',
      branding: false,
      promotion: false,
      convert_urls: false,
      content_style: 'body{font-family:Inter,system-ui,sans-serif;font-size:15px;line-height:1.6}',
      setup: function (editor) {
        editor.ui.registry.addButton('dshmedia', {
          icon: 'gallery',
          tooltip: 'Ảnh từ thư viện',
          onAction: function () {
            if (!window.DSHMediaPicker) {
              return;
            }
            window.DSHMediaPicker.open(function (item) {
              var alt = (item.name || '').replace(/"/g, '&quot;');
              editor.insertContent('<img src="' + item.url + '" alt="' + alt + '" />');
            });
          }
        });

        editor.ui.registry.addButton('dshfile', {
          icon: 'upload',
          tooltip: 'Tệp đính kèm / PDF',
          onAction: function () {
            if (!window.DSHMediaPicker) {
              return;
            }
            window.DSHMediaPicker.open(function (item) {
              var selected = editor.selection.getContent({ format: 'text' });
              var label = (selected || item.name || 'Tải tệp về').replace(/</g, '&lt;');
              var url = item.url.replace(/"/g, '&quot;');
              editor.insertContent(
                '<a href="' + url + '" target="_blank" rel="noopener" download>' + label + '</a>'
              );
            }, { scope: 'doc', accept: 'application/pdf' });
          }
        });
      }
    });

    // Đồng bộ mọi editor về textarea trước khi submit.
    var form = editors[0].closest('form');
    if (form) {
      form.addEventListener('submit', function () {
        window.tinymce.triggerSave();
      });
    }
  }

  // -------------------------------------------- bật/tắt khối theo danh mục
  function initConditionalBoxes() {
    var checklist = document.querySelector('[data-category-checklist]');
    if (!checklist) {
      return;
    }
    var projectBox = document.querySelector('[data-project-box]');
    var investorBox = document.querySelector('[data-investor-box]');

    function update() {
      var hasProject = false;
      var hasInvestor = false;
      checklist.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
        if (!cb.checked) {
          return;
        }
        if (cb.dataset.projectCat === '1') {
          hasProject = true;
        }
        if (cb.dataset.investorCat === '1') {
          hasInvestor = true;
        }
      });
      if (projectBox) {
        projectBox.hidden = !hasProject;
      }
      if (investorBox) {
        investorBox.hidden = !hasInvestor;
      }
    }

    checklist.addEventListener('change', update);
    update();
  }

  // -------------------------------------------- file đính kèm
  function attachmentItemHtml(item) {
    return '<div class="attachment-item d-flex align-items-center gap-2 mb-1" data-attachment-item>' +
      '<input type="hidden" name="NewsPost[attachmentIds][]" value="' + parseInt(item.id, 10) + '" />' +
      '<i class="fa fa-file-pdf-o text-danger"></i>' +
      '<a href="' + escapeHtml(item.url) + '" target="_blank" rel="noopener" class="flex-grow-1 text-truncate">' +
      escapeHtml(item.name || 'Tệp đính kèm') + '</a>' +
      '<button type="button" class="btn btn-sm btn-outline-danger" data-attachment-remove title="Bỏ file">' +
      '<i class="fa fa-times"></i></button>' +
      '</div>';
  }

  function initAttachments() {
    var list = document.querySelector('[data-attachment-list]');
    var addBtn = document.querySelector('[data-attachment-add]');
    if (!list || !addBtn) {
      return;
    }

    addBtn.addEventListener('click', function () {
      if (!window.DSHMediaPicker) {
        return;
      }
      window.DSHMediaPicker.open(function (item) {
        // Không thêm trùng cùng một file.
        if (list.querySelector('input[value="' + parseInt(item.id, 10) + '"]')) {
          return;
        }
        list.insertAdjacentHTML('beforeend', attachmentItemHtml(item));
      }, { scope: 'doc', accept: 'application/pdf' });
    });

    list.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-attachment-remove]');
      if (!btn) {
        return;
      }
      var item = btn.closest('[data-attachment-item]');
      if (item) {
        item.remove();
      }
    });
  }

  function init() {
    initSlug();
    initEditor();
    initConditionalBoxes();
    initAttachments();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
