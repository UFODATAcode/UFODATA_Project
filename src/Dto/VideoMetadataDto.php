<?php

namespace App\Dto;

class VideoMetadataDto
{
    private ?string $dataFormat = null;
    private ?string $fileFormat = null;
    private ?string $playTimeString = null;
    private ?float $playTimeSeconds = null;
    private ?float $bitRate = null;
    private ?string $bitRateMode = null;
    private ?int $width = null;
    private ?int $height = null;
    private ?int $totalFrames = null;
    private ?float $frameRate = null;
    private ?string $codecName = null;
    private ?float $pixelAspectRatio = null;
    private ?int $bitsPerSample = null;
    private ?float $compressionRatio = null;

    public function getDataFormat(): ?string
    {
        return $this->dataFormat;
    }

    public function setDataFormat(?string $dataFormat): VideoMetadataDto
    {
        $this->dataFormat = $dataFormat;
        return $this;
    }

    public function getFileFormat(): ?string
    {
        return $this->fileFormat;
    }

    public function setFileFormat(?string $fileFormat): VideoMetadataDto
    {
        $this->fileFormat = $fileFormat;
        return $this;
    }

    public function getPlayTimeString(): ?string
    {
        return $this->playTimeString;
    }

    public function setPlayTimeString(?string $playTimeString): VideoMetadataDto
    {
        $this->playTimeString = $playTimeString;
        return $this;
    }

    public function getPlayTimeSeconds(): ?float
    {
        return $this->playTimeSeconds;
    }

    public function setPlayTimeSeconds(?float $playTimeSeconds): VideoMetadataDto
    {
        $this->playTimeSeconds = $playTimeSeconds;
        return $this;
    }

    public function getBitRate(): ?float
    {
        return $this->bitRate;
    }

    public function setBitRate(?float $bitRate): VideoMetadataDto
    {
        $this->bitRate = $bitRate;
        return $this;
    }

    public function getBitRateMode(): ?string
    {
        return $this->bitRateMode;
    }

    public function setBitRateMode(?string $bitRateMode): VideoMetadataDto
    {
        $this->bitRateMode = $bitRateMode;
        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): VideoMetadataDto
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): VideoMetadataDto
    {
        $this->height = $height;
        return $this;
    }

    public function getTotalFrames(): ?int
    {
        return $this->totalFrames;
    }

    public function setTotalFrames(?int $totalFrames): VideoMetadataDto
    {
        $this->totalFrames = $totalFrames;
        return $this;
    }

    public function getFrameRate(): ?float
    {
        return $this->frameRate;
    }

    public function setFrameRate(?float $frameRate): VideoMetadataDto
    {
        $this->frameRate = $frameRate;
        return $this;
    }

    public function getCodecName(): ?string
    {
        return $this->codecName;
    }

    public function setCodecName(?string $codecName): VideoMetadataDto
    {
        $this->codecName = $codecName;
        return $this;
    }

    public function getPixelAspectRatio(): ?float
    {
        return $this->pixelAspectRatio;
    }

    public function setPixelAspectRatio(?float $pixelAspectRatio): VideoMetadataDto
    {
        $this->pixelAspectRatio = $pixelAspectRatio;
        return $this;
    }

    public function getBitsPerSample(): ?int
    {
        return $this->bitsPerSample;
    }

    public function setBitsPerSample(?int $bitsPerSample): VideoMetadataDto
    {
        $this->bitsPerSample = $bitsPerSample;
        return $this;
    }

    public function getCompressionRatio(): ?float
    {
        return $this->compressionRatio;
    }

    public function setCompressionRatio(?float $compressionRatio): VideoMetadataDto
    {
        $this->compressionRatio = $compressionRatio;
        return $this;
    }
}