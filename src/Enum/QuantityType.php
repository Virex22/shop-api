<?php

namespace App\Enum;

enum QuantityType: string
{
    case UNIT = 'unit';
    case WEIGHT = 'weight';
    case LIQUID = 'liquid';

    public static function validate(string $quantityType): bool
    {
        return in_array($quantityType, [
            'unit',
            'weight',
            'liquid',
        ]);
    }
}
