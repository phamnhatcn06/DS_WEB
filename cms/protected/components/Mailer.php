<?php
/**
 * Gửi email hệ thống (HTML) bằng hàm mail() của PHP.
 *
 * Dự án chạy offline/local, không cấu hình SMTP nên trong môi trường phát triển
 * email có thể không thực sự gửi đi. Vì vậy mọi email đều được GHI LOG (category
 * 'mailer') để lấy lại nội dung/link khi cần; production chỉ cần bật mail() hoặc
 * thay phần gửi bằng SMTP thật.
 */
class Mailer
{
    /**
     * @param string $to      email người nhận
     * @param string $subject tiêu đề
     * @param string $htmlBody nội dung HTML
     * @return bool true nếu mail() nhận gửi (không đảm bảo đã tới nơi)
     */
    public static function send($to, $subject, $htmlBody)
    {
        $params    = Yii::app()->params;
        $fromEmail = isset($params['mailFromEmail']) ? $params['mailFromEmail'] : 'no-reply@localhost';
        $fromName  = isset($params['mailFromName']) ? $params['mailFromName'] : Yii::app()->name;

        // =RFC 2047= mã hoá tên hiển thị (có dấu tiếng Việt) trong header From/Subject.
        $encodedFrom    = '=?UTF-8?B?' . base64_encode($fromName) . '?=';
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $headers = array(
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $encodedFrom . ' <' . $fromEmail . '>',
        );

        // Luôn log để dev lấy lại nội dung/link kể cả khi mail() không hoạt động.
        Yii::log(
            "Gửi email tới {$to}\nTiêu đề: {$subject}\n" . strip_tags($htmlBody),
            CLogger::LEVEL_INFO, 'mailer'
        );

        $sent = @mail($to, $encodedSubject, $htmlBody, implode("\r\n", $headers));

        if (!$sent) {
            Yii::log("mail() không gửi được email tới {$to}", CLogger::LEVEL_WARNING, 'mailer');
        }

        return (bool) $sent;
    }
}
