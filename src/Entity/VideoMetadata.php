<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
class VideoMetadata extends MeasurementMetadata
{
    #[ORM\Column(length: 8)]
    private string $format;

    #[ORM\Column(length: 16, nullable: true)]
    private ?string $playTimeString = null;

    #[ORM\Column(nullable: true)]
    private ?float $playTimeSeconds = null;

    #[ORM\Column(nullable: true)]
    private ?float $bitRate = null;

    #[ORM\Column(nullable: true)]
    private ?string $bitRateMode = null;

    #[ORM\Column(nullable: true)]
    private ?int $width = null;

    #[ORM\Column(nullable: true)]
    private ?int $height = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalFrames = null;

    #[ORM\Column(nullable: true)]
    private ?float $frameRate = null;

    #[ORM\Column(nullable: true)]
    private ?string $codecName = null;

    #[ORM\Column(nullable: true)]
    private ?float $pixelAspectRatio = null;

    #[ORM\Column(nullable: true)]
    private ?int $bitsPerSample = null;

    #[ORM\Column(nullable: true)]
    private ?float $compressionRatio = null;

    public function __construct(
        UuidInterface $uuid,
        string $format,
    ) {
        parent::__construct($uuid);
        $this->format = $format;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getPlayTimeString(): ?string
    {
        return $this->playTimeString;
    }

    public function setPlayTimeString(?string $playTimeString): self
    {
        $this->playTimeString = $playTimeString;

        return $this;
    }

    public function getPlayTimeSeconds(): ?float
    {
        return $this->playTimeSeconds;
    }

    public function setPlayTimeSeconds(?float $playTimeSeconds): self
    {
        $this->playTimeSeconds = $playTimeSeconds;

        return $this;
    }

    public function getBitRate(): ?float
    {
        return $this->bitRate;
    }

    public function setBitRate(?float $bitRate): self
    {
        $this->bitRate = $bitRate;

        return $this;
    }

    public function getBitRateMode(): ?string
    {
        return $this->bitRateMode;
    }

    public function setBitRateMode(?string $bitRateMode): VideoMetadata
    {
        $this->bitRateMode = $bitRateMode;
        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): VideoMetadata
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): VideoMetadata
    {
        $this->height = $height;
        return $this;
    }

    public function getTotalFrames(): ?int
    {
        return $this->totalFrames;
    }

    public function setTotalFrames(?int $totalFrames): VideoMetadata
    {
        $this->totalFrames = $totalFrames;
        return $this;
    }

    public function getFrameRate(): ?float
    {
        return $this->frameRate;
    }

    public function setFrameRate(?float $frameRate): VideoMetadata
    {
        $this->frameRate = $frameRate;
        return $this;
    }

    public function getCodecName(): ?string
    {
        return $this->codecName;
    }

    public function setCodecName(?string $codecName): VideoMetadata
    {
        $this->codecName = $codecName;
        return $this;
    }

    public function getPixelAspectRatio(): ?float
    {
        return $this->pixelAspectRatio;
    }

    public function setPixelAspectRatio(?float $pixelAspectRatio): VideoMetadata
    {
        $this->pixelAspectRatio = $pixelAspectRatio;
        return $this;
    }

    public function getBitsPerSample(): ?int
    {
        return $this->bitsPerSample;
    }

    public function setBitsPerSample(?int $bitsPerSample): VideoMetadata
    {
        $this->bitsPerSample = $bitsPerSample;
        return $this;
    }

    public function getCompressionRatio(): ?float
    {
        return $this->compressionRatio;
    }

    public function setCompressionRatio(?float $compressionRatio): VideoMetadata
    {
        $this->compressionRatio = $compressionRatio;
        return $this;
    }
}
