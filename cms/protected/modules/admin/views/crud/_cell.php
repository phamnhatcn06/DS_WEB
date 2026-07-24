<?php
/* @var $item CActiveRecord */
/* @var $column array */

$name = $column['name'];
$type = isset($column['type']) ? $column['type'] : 'text';

switch ($type) {

    // Ảnh thu nhỏ từ quan hệ media
    case 'image':
        $media = $item->$name;
        if ($media instanceof MediaFile) {
            $class = 'grid-thumb' . ($media->getIsVector() ? ' grid-thumb--contain' : '');
            echo CHtml::image($media->getPublicUrl(), $media->alt_text,
                array('class' => $class, 'loading' => 'lazy'));
        } else {
            echo '<span class="text-muted small">—</span>';
        }
        break;

    // Trạng thái bật/tắt
    case 'bool':
        echo $item->$name
            ? '<span class="badge bg-success-subtle text-success-emphasis">Hiển thị</span>'
            : '<span class="badge bg-secondary-subtle text-secondary-emphasis">Đang ẩn</span>';
        break;

    // Nhãn phân loại
    case 'badge':
        $text = isset($column['value']) ? call_user_func($column['value'], $item) : $item->$name;
        echo $text === null || $text === ''
            ? '<span class="text-muted small">—</span>'
            : '<span class="badge bg-light text-dark border">' . CHtml::encode($text) . '</span>';
        break;

    case 'date':
        $value = $item->$name;
        echo $value ? date('d/m/Y', strtotime($value)) : '<span class="text-muted small">—</span>';
        break;

    case 'number':
        echo number_format((float) $item->$name, 0, ',', '.');
        break;

    // Giá trị tính bằng callback
    case 'callback':
        echo call_user_func($column['value'], $item);
        break;

    // Văn bản có nhấn mạnh dòng đầu
    case 'primary':
        echo '<strong>' . CHtml::encode((string) $item->$name) . '</strong>';
        if (isset($column['sub'])) {
            $sub = call_user_func($column['sub'], $item);
            if ($sub !== '' && $sub !== null) {
                echo '<br /><span class="text-muted small">' . CHtml::encode($sub) . '</span>';
            }
        }
        break;

    default:
        $value = (string) $item->$name;
        echo $value === ''
            ? '<span class="text-muted small">—</span>'
            : CHtml::encode(TextHelper::truncate($value, isset($column['limit']) ? $column['limit'] : 90));
}
