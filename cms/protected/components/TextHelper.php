<?php
/**
 * Tiện ích xử lý chuỗi tiếng Việt.
 */
class TextHelper
{
    /** Bảng chuyển ký tự có dấu sang không dấu. */
    private static $accentMap = array(
        'à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a',
        'ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
        'è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e',
        'ệ'=>'e','ể'=>'e','ễ'=>'e',
        'ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
        'ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o',
        'ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
        'ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u',
        'ự'=>'u','ử'=>'u','ữ'=>'u',
        'ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
        'đ'=>'d',
    );

    /**
     * Bỏ dấu tiếng Việt.
     */
    public static function removeAccents($text)
    {
        $text = mb_strtolower((string) $text, 'UTF-8');
        return strtr($text, self::$accentMap);
    }

    /**
     * Sinh slug kebab-case từ chuỗi tiếng Việt.
     * Ví dụ: "Đầu tư BOT & Hạ tầng" → "dau-tu-bot-ha-tang"
     */
    public static function slugify($text)
    {
        $text = self::removeAccents($text);
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
        return trim($text, '-');
    }

    /**
     * Cắt chuỗi theo số ký tự, không cắt giữa từ.
     */
    public static function truncate($text, $length = 120, $suffix = '…')
    {
        $text = trim(strip_tags((string) $text));
        if (mb_strlen($text, 'UTF-8') <= $length) {
            return $text;
        }
        $cut = mb_substr($text, 0, $length, 'UTF-8');
        $lastSpace = mb_strrpos($cut, ' ', 0, 'UTF-8');
        if ($lastSpace !== false) {
            $cut = mb_substr($cut, 0, $lastSpace, 'UTF-8');
        }
        return $cut . $suffix;
    }

    /**
     * Cắt chuỗi theo số TỪ (không cắt giữa từ), bỏ HTML và gộp khoảng trắng.
     * Dùng cho trích dẫn card: "hiển thị N từ đầu tiên rồi thêm …".
     */
    public static function truncateWords($text, $words = 30, $suffix = '…')
    {
        $text = strip_tags((string) $text);
        // Giải mã thực thể HTML (&ecirc; &aacute; &nbsp; &ndash; …) về ký tự thật,
        // nếu không sẽ hiện nguyên "T&ecirc;n d&aacute;n" trên thẻ tin.
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // Gộp mọi khoảng trắng — kể cả non-breaking space (&nbsp; = U+00A0).
        $text = trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text));
        if ($text === '') {
            return '';
        }
        $words = max(1, (int) $words);
        $parts = preg_split('/\s+/u', $text);
        if (count($parts) <= $words) {
            return $text;
        }
        return implode(' ', array_slice($parts, 0, $words)) . $suffix;
    }

    /**
     * Định dạng số tiền VNĐ dạng rút gọn: 4213000000000 → "4.213 tỷ đồng"
     */
    public static function formatCurrencyShort($amount)
    {
        $amount = (float) $amount;
        if ($amount <= 0) {
            return '';
        }
        if ($amount >= 1000000000) {
            return number_format($amount / 1000000000, 0, ',', '.') . ' tỷ đồng';
        }
        if ($amount >= 1000000) {
            return number_format($amount / 1000000, 0, ',', '.') . ' triệu đồng';
        }
        return number_format($amount, 0, ',', '.') . ' đồng';
    }
}
