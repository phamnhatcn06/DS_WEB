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
