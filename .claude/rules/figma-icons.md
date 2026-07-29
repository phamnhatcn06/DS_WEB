# Quy tắc Icon & Asset — Bám sát Figma 100%

> Quy tắc **bắt buộc**. Áp dụng cho mọi icon, ảnh, logo, đồ hoạ khi dựng UI từ thiết kế Figma.

## 🎯 Nguyên tắc cốt lõi

1. **Icon phải dùng ĐÚNG icon trong Figma — giống 100%.**
   - **TUYỆT ĐỐI KHÔNG tự chọn / đoán / thay thế** bằng một icon "gần giống" (ví dụ: không lấy `bi-bullseye` thay cho icon lá cờ, không lấy `giatri-icon-person` thay cho icon bắt tay).
   - Nếu Figma dùng icon nào thì **export đúng icon SVG đó** từ Figma về `assets/images/` và tham chiếu qua đường dẫn tương đối.

2. **Cách lấy đúng icon từ Figma (asset server `localhost:3845` đang chạy):**
   - Gọi `get_design_context` trên node chứa icon → lấy URL asset (`http://localhost:3845/assets/<hash>.svg`).
   - Tải về: `curl -sf "<url>" -o assets/images/<tên-mô-tả>.svg`.
   - SVG export giữ nguyên màu Figma qua `fill="var(--fill-0, #HEX)"` (fallback là màu thật) → dùng bằng `<img src>` là màu hiển thị đúng.

3. **Ảnh raster (png/jpg) trong Figma:** tải về rồi convert sang `.webp` (`quality ≈ 82`), lưu `assets/images/`. Không hotlink `localhost:3845`, không dùng ảnh placeholder khác thay cho ảnh thật khi asset server đang online.

4. **Kích thước & tỉ lệ:** giữ đúng `width`/`height` (px) và tỉ lệ như Figma. Không ép `width:32; height:32` lên icon có tỉ lệ khác (gây méo) — dùng đúng số đo, hoặc `height` cố định + `width:auto`.

5. **Chỉ khi asset server offline** mới được dùng placeholder local tạm; phải ghi chú TODO và thay bằng asset thật ngay khi có.

## ❌ Tránh
- ❌ Thay icon Figma bằng Bootstrap Icons / FontAwesome "cho giống giống".
- ❌ Đoán tên icon theo ngữ nghĩa tiêu đề (Trách nhiệm → tự chọn icon bắt tay bất kỳ).
- ❌ Ép icon vào khung vuông làm méo tỉ lệ gốc.
- ❌ Hotlink asset Figma / CDN — luôn export về `assets/images/`.
