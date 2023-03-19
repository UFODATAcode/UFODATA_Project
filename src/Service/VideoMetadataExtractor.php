<?php

namespace App\Service;

use App\Dto\VideoMetadataDto;

class VideoMetadataExtractor
{
    private readonly VideoMetadataExtractorEngine $engine;

    public function __construct(VideoMetadataExtractorEngine $engine)
    {
        $this->engine = $engine;
    }

    public function extract(string $pathToFile): VideoMetadataDto
    {
        $extractedMetadata = $this->engine->analyze($pathToFile);

        return (new VideoMetadataDto())
            ->setFileFormat($extractedMetadata['fileformat'] ?? null)
            ->setDataFormat($extractedMetadata['video']['dataformat'] ?? null)
            ->setPlayTimeString($extractedMetadata['playtime_string'] ?? null)
            ->setPlayTimeSeconds($extractedMetadata['playtime_seconds'] ?? null)
            ->setBitRate($extractedMetadata['video']['bitrate'] ?? null)
            ->setBitRateMode($extractedMetadata['video']['bitrate_mode'] ?? null)
            ->setWidth($extractedMetadata['video']['resolution_x'] ?? null)
            ->setHeight($extractedMetadata['video']['resolution_y'] ?? null)
            ->setTotalFrames($extractedMetadata['video']['total_frames'] ?? null)
            ->setFrameRate($extractedMetadata['video']['frame_rate'] ?? null)
            ->setCodecName($extractedMetadata['video']['codec'] ?? null)
            ->setPixelAspectRatio($extractedMetadata['video']['pixel_aspect_ratio'] ?? null)
            ->setBitsPerSample($extractedMetadata['video']['bits_per_sample'] ?? null)
            ->setCompressionRatio($extractedMetadata['video']['compression_ratio'] ?? null);
    }
}