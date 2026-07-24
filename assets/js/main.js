/* ==========================================================================
   main.js — Tương tác cho trang chủ Đông Sơn Holdings (DSH)
   Gồm: scroll reveal, parallax, thanh tiến độ, back-to-top, scroll spy,
   header glassmorphism, carousel và tab lọc tin tức.
   ========================================================================== */

(function () {
  'use strict';

  var STAGGER_STEP_MS = 90;
  var SUPPORTS_OBSERVER = 'IntersectionObserver' in window;
  var PREFERS_REDUCED_MOTION =
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /**
   * Gán reveal cho các nhóm phần tử lặp lại mà không phải sửa từng thẻ trong
   * HTML. Mỗi mục trong nhóm nhận độ trễ tăng dần để tạo hiệu ứng stagger.
   * @param {string} selector - selector của các mục trong một nhóm
   * @param {string} direction - biến thể hướng: up | left | right | zoom | blur
   */
  function assignReveal(selector, direction) {
    document.querySelectorAll(selector).forEach(function (group) {
      Array.prototype.forEach.call(group.children, function (child, index) {
        if (child.hasAttribute('data-reveal')) {
          return;
        }
        child.setAttribute('data-reveal', direction);
        child.style.setProperty('--reveal-delay', index * STAGGER_STEP_MS + 'ms');
      });
    });
  }

  /** Khai báo các nhóm cần reveal theo thứ tự — chỉ chạy một lần lúc khởi tạo. */
  function markRevealTargets() {
    assignReveal('.linhvuc-grid .row', 'up');
    assignReveal('.giatri-inner .row-cols-1', 'up');
    assignReveal('.doitac-inner .row', 'zoom');
    assignReveal('.footer-main .row', 'up');
  }

  /**
   * Reveal khi cuộn vào viewport — một observer duy nhất cho cả khối lớn
   * (.fade-section) lẫn phần tử lẻ ([data-reveal]). Dùng IntersectionObserver,
   * không poll sự kiện scroll.
   */
  function initScrollReveal() {
    var targets = document.querySelectorAll('.fade-section, [data-reveal]');
    if (!targets.length) {
      return;
    }

    if (!SUPPORTS_OBSERVER || PREFERS_REDUCED_MOTION) {
      targets.forEach(function (target) { target.classList.add('is-visible'); });
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          return;
        }
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    targets.forEach(function (target) { observer.observe(target); });
  }

  /**
   * Parallax nhẹ cho ảnh nền [data-parallax] — cập nhật biến --parallax-y
   * trong một vòng requestAnimationFrame duy nhất, không đọc layout mỗi scroll.
   */
  function initParallax() {
    var layers = document.querySelectorAll('[data-parallax]');
    if (!layers.length || PREFERS_REDUCED_MOTION) {
      return;
    }

    var ticking = false;

    function update() {
      ticking = false;
      var viewportHeight = window.innerHeight;

      layers.forEach(function (layer) {
        var rect = layer.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > viewportHeight) {
          return;
        }
        var strength = parseFloat(layer.dataset.parallax) || 0.15;
        var progress = (rect.top + rect.height / 2 - viewportHeight / 2) / viewportHeight;
        layer.style.setProperty('--parallax-y', (progress * strength * 100).toFixed(2) + 'px');
      });
    }

    function requestUpdate() {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(update);
      }
    }

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);
    update();
  }

  /** Thanh tiến độ cuộn trang ở đỉnh màn hình. */
  function initScrollProgress() {
    var bar = document.createElement('div');
    bar.className = 'scroll-progress';
    bar.setAttribute('aria-hidden', 'true');
    document.body.appendChild(bar);

    var ticking = false;

    function update() {
      ticking = false;
      var scrollable = document.documentElement.scrollHeight - window.innerHeight;
      var ratio = scrollable > 0 ? window.scrollY / scrollable : 0;
      bar.style.setProperty('--progress', Math.min(ratio, 1).toFixed(4));
    }

    function requestUpdate() {
      if (!ticking) {
        ticking = true;
        window.requestAnimationFrame(update);
      }
    }

    window.addEventListener('scroll', requestUpdate, { passive: true });
    window.addEventListener('resize', requestUpdate);
    update();
  }

  /** Nút quay lại đầu trang — hiện khi đã rời khỏi vùng hero. */
  function initBackToTop() {
    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'back-to-top';
    button.setAttribute('aria-label', 'Lên đầu trang');
    button.innerHTML = '<i class="bi bi-arrow-up" aria-hidden="true"></i>';
    document.body.appendChild(button);

    button.addEventListener('click', function () {
      window.scrollTo({
        top: 0,
        behavior: PREFERS_REDUCED_MOTION ? 'auto' : 'smooth',
      });
    });

    var sentinel = document.querySelector('.header-sentinel');
    if (!sentinel || !SUPPORTS_OBSERVER) {
      button.classList.add('is-shown');
      return;
    }

    var observer = new IntersectionObserver(function (entries) {
      button.classList.toggle('is-shown', !entries[0].isIntersecting);
    }, { threshold: 0, rootMargin: '-50% 0px 0px 0px' });

    observer.observe(sentinel);
  }

  /**
   * Scroll spy: đánh dấu link nav của section đang xem bằng class .is-active.
   * Dùng IntersectionObserver với dải quan sát nằm giữa viewport.
   */
  function initScrollSpy() {
    var links = document.querySelectorAll('.site-header .nav-link[href^="#"]');
    if (!links.length || !SUPPORTS_OBSERVER) {
      return;
    }

    var linkBySectionId = {};
    var sections = [];

    links.forEach(function (link) {
      var section = document.querySelector(link.getAttribute('href'));
      if (section) {
        linkBySectionId[section.id] = link;
        sections.push(section);
      }
    });

    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) {
          return;
        }
        links.forEach(function (link) { link.classList.remove('is-active'); });
        linkBySectionId[entry.target.id].classList.add('is-active');
      });
    }, { rootMargin: '-45% 0px -50% 0px' });

    sections.forEach(function (section) { observer.observe(section); });
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

  /**
   * Slider ảnh cuộn ngang có scroll-snap (Section 5 — Dự án tiêu biểu).
   * Mở đầu căn giữa thẻ ở giữa dải để hai thẻ ngoài cùng bị cắt một nửa;
   * nút prev/next cuộn đi đúng một thẻ.
   */
  function initScrollSliders() {
    var sliders = document.querySelectorAll('[data-duan-slider]');

    sliders.forEach(function (slider) {
      var track = slider.querySelector('.duan-gallery');
      var items = slider.querySelectorAll('.duan-item');
      if (!track || !items.length) {
        return;
      }

      function scrollByOneCard(direction) {
        var gap = parseFloat(getComputedStyle(track).columnGap) || 0;
        track.scrollBy({ left: direction * (items[0].offsetWidth + gap), behavior: 'smooth' });
      }

      function centerMiddleCard() {
        var middle = items[Math.floor(items.length / 2)];
        track.scrollLeft = middle.offsetLeft - (track.clientWidth - middle.offsetWidth) / 2;
      }

      var prevButton = slider.querySelector('[data-duan-prev]');
      var nextButton = slider.querySelector('[data-duan-next]');

      if (prevButton) {
        prevButton.addEventListener('click', function () { scrollByOneCard(-1); });
      }
      if (nextButton) {
        nextButton.addEventListener('click', function () { scrollByOneCard(1); });
      }

      centerMiddleCard();
      window.addEventListener('resize', centerMiddleCard);
    });
  }

  /**
   * Tab lọc tin tức theo danh mục (Section 9). Ẩn/hiện card qua class
   * .is-hidden dựa trên [data-category], không tải lại trang.
   */
  function initNewsFilter() {
    var filterBars = document.querySelectorAll('[data-news-filter]');

    filterBars.forEach(function (bar) {
      var buttons = bar.querySelectorAll('[data-filter]');
      var items = document.querySelectorAll('.news-item');
      var section = bar.closest('.tintuc');

      var FILTER_FADE_MS = PREFERS_REDUCED_MOTION ? 0 : 300;

      function isMatched(item, category) {
        return category === 'all' || item.dataset.category === category;
      }

      /** Bước 1: làm mờ các thẻ sắp bị ẩn trước khi gỡ khỏi luồng layout. */
      function fadeOutUnmatched(category) {
        items.forEach(function (item) {
          item.classList.toggle('is-leaving', !isMatched(item, category));
        });
      }

      /** Bước 2: ẩn hẳn thẻ không khớp và cho thẻ khớp hiện lên có stagger. */
      function swapVisibility(category) {
        var enterIndex = 0;

        // Bỏ độ lệch trang trí của cột trái khi lưới chỉ còn một danh mục.
        if (section) {
          section.classList.toggle('is-filtered', category !== 'all');
        }

        items.forEach(function (item) {
          var matched = isMatched(item, category);
          item.classList.toggle('is-hidden', !matched);
          item.classList.remove('is-entering');

          if (!matched) {
            return;
          }
          item.classList.remove('is-leaving');
          item.style.setProperty('--reveal-delay', enterIndex * STAGGER_STEP_MS + 'ms');
          // Ép trình duyệt tính lại để animation chạy lại khi lọc liên tiếp.
          void item.offsetWidth;
          item.classList.add('is-entering');
          enterIndex += 1;
        });
      }

      function applyFilter(category) {
        fadeOutUnmatched(category);
        window.setTimeout(function () { swapVisibility(category); }, FILTER_FADE_MS);
      }

      buttons.forEach(function (button) {
        button.addEventListener('click', function () {
          buttons.forEach(function (other) {
            var isCurrent = other === button;
            other.classList.toggle('active', isCurrent);
            other.setAttribute('aria-selected', String(isCurrent));
          });
          applyFilter(button.dataset.filter);
        });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    markRevealTargets();
    initScrollReveal();
    initHeaderGlass();
    initScrollSpy();
    initScrollProgress();
    initBackToTop();
    initParallax();
    initCustomSliders();
    initScrollSliders();
    initNewsFilter();
  });
})();
