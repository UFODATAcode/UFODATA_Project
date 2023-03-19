<?php

namespace App\Factory;

use App\Dto\VideoMetadataDto;
use App\Entity\VideoMetadata;
use Ramsey\Uuid\Uuid;

class VideoMetadataFactory
{
    public static function fromDto(VideoMetadataDto $dto): VideoMetadata
    {
        $metadata = new VideoMetadata(
            Uuid::uuid4(),
            $dto->getDataFormat() ?? $dto->getFileFormat(),
        );
        $metadata
            ->setPlayTimeString($dto->getPlayTimeString())
            ->setPlayTimeSeconds($dto->getPlayTimeSeconds())
            ->setBitRate($dto->getBitRate())
            ->setBitRateMode($dto->getBitRateMode())
            ->setWidth($dto->getWidth())
            ->setHeight($dto->getHeight())
            ->setTotalFrames($dto->getTotalFrames())
            ->setFrameRate($dto->getFrameRate())
            ->setCodecName($dto->getCodecName())
            ->setPixelAspectRatio($dto->getPixelAspectRatio())
            ->setBitsPerSample($dto->getBitsPerSample())
            ->setCompressionRatio($dto->getCompressionRatio());

        return $metadata;
    }
}