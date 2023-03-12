<?php

namespace App\Enum;

enum MeasurementType: string
{
    case RadioFrequencySpectrum = 'mcrfs';
    case MissionControlData = 'mcdat';
    case MissionControlAdsBFlightTracking = 'mcflt';
    case MissionControlWeather = 'mcwth';
    case Video = 'video';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return \array_map(fn($case) => $case->value, self::cases());
    }
}
