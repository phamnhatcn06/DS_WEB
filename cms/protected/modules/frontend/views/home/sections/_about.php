<?php
/**
 * Section 3 — Giới thiệu · Sứ mệnh & Tầm nhìn.
 *
 * Chưa có nguồn dữ liệu trong payload (quote/sứ mệnh/tầm nhìn sẽ đọc từ
 * SiteSetting ở bước sau) — hiện giữ nội dung tĩnh.
 *
 * @var Controller $this
 */
$root = $this->assetsBase();
?>
    <!-- ===== Section 3: Giới thiệu · Sứ mệnh & Tầm nhìn ===== -->
    <section id="gioi-thieu" class="about section fade-section">
      <div class="container">

        <!-- Quote -->
        <blockquote class="about-quote" data-reveal="blur">
          <span class="quote-mark" aria-hidden="true">&ldquo;&rdquo;</span>
          <p>&ldquo;Trở thành doanh nghiệp uy tín trong <span class="hl">lĩnh vực năng lượng, bất động sản và xây lắp</span>. Kiến tạo các giá trị bền vững và đồng hành cùng sự phát triển của xã hội.&rdquo;</p>
        </blockquote>

        <!-- Card 1: Sứ mệnh (desktop: chữ trái · ảnh phải; mobile: ảnh trên) -->
        <article class="about-card" data-reveal="left">
          <div class="row g-0 align-items-stretch">
            <div class="col-12 col-lg-7 about-media">
              <img src="<?php echo $root; ?>/assets/images/about-construction.webp" alt="Công trình xây dựng của Đông Sơn Holdings" loading="lazy" />
            </div>
            <div class="col-12 col-lg-5 about-text order-lg-first">
              <img src="<?php echo $root; ?>/assets/images/logo-red.webp" alt="" class="card-logo" aria-hidden="true" />
              <h3>Sứ mệnh</h3>
              <p>Kiến tạo giá trị bền vững cho khách hàng, đối tác và cộng đồng.</p>
            </div>
          </div>
        </article>

        <!-- Card 2: Tầm nhìn (desktop: ảnh trái · chữ phải; mobile: ảnh trên) -->
        <article class="about-card" data-reveal="right">
          <div class="row g-0 align-items-stretch">
            <div class="col-12 col-lg-7 about-media">
              <img src="<?php echo $root; ?>/assets/images/about-energy.webp" alt="Dự án năng lượng tái tạo của Đông Sơn Holdings" loading="lazy" />
            </div>
            <div class="col-12 col-lg-5 about-text">
              <img src="<?php echo $root; ?>/assets/images/logo-red.webp" alt="" class="card-logo" aria-hidden="true" />
              <h3>Tầm nhìn</h3>
              <p>Kiến tạo giá trị bền vững cho khách hàng, đối tác và cộng đồng.</p>
            </div>
          </div>
        </article>

      </div>
    </section>
