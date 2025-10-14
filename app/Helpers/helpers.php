<?php

if (!function_exists('convertMatri')) {
    function convertMatri($matri) {
        if (preg_match('/^000([A-Z])(\d+)$/', $matri, $matches)) {
            $prefix = ord($matches[1]) - ord('A') + 10; // تحويل A إلى 10 و B إلى 11 وهكذا
            return $prefix . $matches[2]; // دمج الرقم المحول مع الجزء الرقمي
        }
        return $matri; // إذا لم يكن بالشكل المتوقع، يرجع كما هو
    }
}
