<?php


namespace App\Tests\Unit;

use App\Dto\VideoMetadataDto;
use App\Service\VideoMetadataExtractor;
use App\Tests\UnitTester;
use Codeception\Test\Unit;
use getID3;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class VideoMetadataExtractorTest extends Unit
{
    use ProphecyTrait;

    protected UnitTester $tester;
    private getID3|ObjectProphecy $engine;
    private VideoMetadataExtractor $sut;

    protected function _before(): void
    {
        $this->engine = $this->prophesize(getID3::class);
        $this->sut = new VideoMetadataExtractor($this->engine->reveal());
    }

    public function testExtractVideoMetadata(): void
    {
        $pathToFile = '/path/to/file.avi';
        $extractedMetadata = [
            'playtime_string' => '0:05',
            'playtime_seconds' => 4.99995,
            'fileformat' => 'avix',
            'video' => [
                'dataformat' => 'avi',
                'bitrate_mode' => 'vbr',
                'resolution_x' => 1920,
                'resolution_y' => 1080,
                'total_frames' => 150,
                'frame_rate' => 30.0,
                'fourcc' => 'MJPG',
                'codec' => 'Microsoft Motion JPEG DIB',
                'pixel_aspect_ratio' => 1.0,
                'lossless' => false,
                'bits_per_sample' => 24,
                'bitrate' => 43774082.540825,
                'compression_ratio' => 0.029319703347925,
            ],
        ];
        $this->engine->analyze($pathToFile)->willReturn($extractedMetadata);

        $result = $this->sut->extract($pathToFile);

        $this->tester->assertInstanceOf(VideoMetadataDto::class, $result);
        $this->tester->assertSame($extractedMetadata['playtime_string'], $result->getPlayTimeString());
        $this->tester->assertSame($extractedMetadata['playtime_seconds'], $result->getPlayTimeSeconds());
        $this->tester->assertSame($extractedMetadata['fileformat'], $result->getFileFormat());
        $this->tester->assertSame($extractedMetadata['video']['dataformat'], $result->getDataFormat());
        $this->tester->assertSame($extractedMetadata['video']['bitrate_mode'], $result->getBitRateMode());
        $this->tester->assertSame($extractedMetadata['video']['resolution_x'], $result->getWidth());
        $this->tester->assertSame($extractedMetadata['video']['total_frames'], $result->getTotalFrames());
        $this->tester->assertSame($extractedMetadata['video']['frame_rate'], $result->getFrameRate());
        $this->tester->assertSame($extractedMetadata['video']['codec'], $result->getCodecName());
        $this->tester->assertSame($extractedMetadata['video']['pixel_aspect_ratio'], $result->getPixelAspectRatio());
        $this->tester->assertSame($extractedMetadata['video']['bits_per_sample'], $result->getBitsPerSample());
        $this->tester->assertSame($extractedMetadata['video']['bitrate'], $result->getBitRate());
        $this->tester->assertSame($extractedMetadata['video']['compression_ratio'], $result->getCompressionRatio());
    }
}
