<?php

namespace App\Enums;

class CustomPriceType
{
    const CUSTOM_IKAT = 'custom_ikat';
    const CUSTOM_TANGKAI = 'custom_tangkai';
    const CUSTOM_KHUSUS = 'custom_khusus';

    public static function values(): array
    {
        return [
            self::CUSTOM_IKAT,
            self::CUSTOM_TANGKAI,
            self::CUSTOM_KHUSUS
        ];
    }

    public static function labels(): array
    {
        return [
            self::CUSTOM_IKAT => 'Custom Ikat',
            self::CUSTOM_TANGKAI => 'Custom Tangkai',
            self::CUSTOM_KHUSUS => 'Custom Khusus'
        ];
    }
}
