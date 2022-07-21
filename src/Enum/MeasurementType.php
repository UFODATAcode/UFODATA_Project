<?php

namespace App\Enum;

enum MeasurementType: string
{
    case RadioFrequencySpectrum = 'rfs';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return \array_map(fn($case) => $case->value, self::cases());
    }
}
