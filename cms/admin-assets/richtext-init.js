/**
 * Khởi tạo trình soạn thảo TinyMCE (self-host) cho MỌI textarea có class
 * `.wp-content-editor` trong form CRUD dùng chung (admin.views.crud.form).
 *
 * Tách riêng khỏi news-post-form.js để tái sử dụng cho các module khác
 * (vd: Cột mốc thời gian). Hai nút bổ sung: chèn ảnh từ thư viện (dshmedia)
 * và chèn liên kết tệp/PDF (dshfile) — dùng DSHMediaPicker của admin.js.
 *
 * Nạp cuối trang (defer) sau tinymce.min.js và admin.js.
 */
(function () {
  'use strict';

  function initEditors() {
    if (typeof window.tinymce === 'undefined') {
      return;
    }
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
      height: 420,
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

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initEditors);
  } else {
    initEditors();
  }
})();
