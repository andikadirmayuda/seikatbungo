<?php

namespace App\Enums;

enum RibbonColor: string
{
    case W01 = 'w01'; // Yellow
    case W02 = 'w02'; // Brown
    case W03 = 'w03'; // Silver
    case W04 = 'w04'; // Pink Soft
    case W05 = 'w05'; // Black
    case W06 = 'w06'; // Navy
    case W07 = 'w07'; // Light Blue
    case W08 = 'w08'; // Dark Blue
    case W09 = 'w09'; // Dark Purple
    case W10 = 'w10'; // Light Purple
    case W11 = 'w11'; // Red
    case W12 = 'w12'; // Maroon
    case W13 = 'w13'; // Tosca
    case W14 = 'w14'; // Light Green
    case W15 = 'w15'; // Dark Green
    case W16 = 'w16'; // Lime Green
    case W17 = 'w17'; // Dark Gray
    case W18 = 'w18'; // Dark Brown
    case W19 = 'w19'; // Gold
    case W20 = 'w20'; // White

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getColorCode(string $color): string
    {
        return match ($color) {
            self::W01->value => '#FFD700', // Yellow
            self::W02->value => '#8B4513', // Brown
            self::W03->value => '#C0C0C0', // Silver
            self::W04->value => '#FFB6C1', // Pink Soft
            self::W05->value => '#000000', // Black
            self::W06->value => '#000080', // Navy
            self::W07->value => '#87CEEB', // Light Blue
            self::W08->value => '#00008B', // Dark Blue
            self::W09->value => '#301934', // Dark Purple
            self::W10->value => '#DDA0DD', // Light Purple
            self::W11->value => '#FF0000', // Red
            self::W12->value => '#800000', // Maroon
            self::W13->value => '#40E0D0', // Tosca
            self::W14->value => '#90EE90', // Light Green
            self::W15->value => '#006400', // Dark Green
            self::W16->value => '#32CD32', // Lime Green
            self::W17->value => '#A9A9A9', // Dark Gray
            self::W18->value => '#5C4033', // Dark Brown
            self::W19->value => '#FFD700', // Gold
            self::W20->value => '#FFFFFF', // White
            default => '#FFB6C1', // default pink-soft
        };
    }

    public static function getColorName(?string $color): string
    {
        if (empty($color)) {
            return '-';
        }

        return match ($color) {
            self::W01->value => 'Kuning',
            self::W02->value => 'Coklat',
            self::W03->value => 'Silver',
            self::W04->value => 'Pink Soft',
            self::W05->value => 'Hitam',
            self::W06->value => 'Navy',
            self::W07->value => 'Biru Muda',
            self::W08->value => 'Biru Tua',
            self::W09->value => 'Ungu Tua',
            self::W10->value => 'Ungu Muda',
            self::W11->value => 'Merah',
            self::W12->value => 'Maroon',
            self::W13->value => 'Tosca',
            self::W14->value => 'Hijau Muda',
            self::W15->value => 'Hijau Tua',
            self::W16->value => 'Lime Green',
            self::W17->value => 'Abu-abu Tua',
            self::W18->value => 'Coklat Tua',
            self::W19->value => 'Emas',
            self::W20->value => 'Putih',
            default => $color // Return the code if not found in the list
        };
    }
}
