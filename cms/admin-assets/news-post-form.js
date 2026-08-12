/**
 * Màn hình soạn bài viết kiểu WordPress:
 *  - Tự sinh slug từ tiêu đề (bỏ dấu tiếng Việt) khi người dùng chưa tự sửa slug.
 *  - Khởi tạo trình soạn thảo TinyMCE (self-host) cho ô nội dung.
 *
 * Nạp cuối trang (defer) sau tinymce.min.js và admin.js.
 */
(function () {
  'use strict';

  // ------------------------------------------------------------ slug tiếng Việt
  function removeVietnameseTones(str) {
    return str
      .normalize('NFD')
      .replace(/[̀-ͯ]/g, '') // bỏ dấu thanh (combining marks)
      .replace(/đ/g, 'd')          // đ
      .replace(/Đ/g, 'D');         // Đ
  }

  function slugify(text) {
    return removeVietnameseTones(String(text))
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }

  function initSlug() {
    var titleInput = document.querySelector('[data-slug-source]');
    var slugInput = document.querySelector('[data-slug-target]');
    if (!titleInput || !slugInput) {
      return;
    }

    // Người dùng gõ tay vào slug → ngừng tự sinh để không ghi đè.
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
    var textarea = document.querySelector('.wp-content-editor');
    if (!textarea) {
      return;
    }

    window.tinymce.init({
      target: textarea,
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
        // Nút "Ảnh từ thư viện" — dùng chung media picker sẵn có, chèn ảnh vào nội dung.
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
      }
    });

    // Đồng bộ nội dung editor về textarea trước khi submit.
    var form = textarea.closest('form');
    if (form) {
      form.addEventListener('submit', function () {
        window.tinymce.triggerSave();
      });
    }
  }

  function init() {
    initSlug();
    initEditor();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
