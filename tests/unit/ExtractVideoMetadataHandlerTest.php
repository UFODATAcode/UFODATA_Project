<?php

namespace App\Tests\Unit;

use App\Contract\MeasurementMetadataRepositoryInterface;
use App\Contract\MeasurementRepositoryInterface;
use App\Dto\VideoMetadataDto;
use App\Entity\MissionControlData;
use App\Entity\Video;
use App\Entity\VideoMetadata;
use App\Event\VideoAddedEvent;
use App\Handler\ExtractVideoMetadataHandler;
use App\Service\VideoMetadataExtractor;
use App\Tests\UnitTester;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\File\File;

class ExtractVideoMetadataHandlerTest extends Unit
{
    use ProphecyTrait;

    private const MEASUREMENT_UUID = '43ac22b2-1fd7-46c3-b933-b2bbc75fbe43';

    protected UnitTester $tester;
    private UuidInterface $measurementUuid;
    private ObjectProphecy|MeasurementRepositoryInterface $measurementRepository;
    private ObjectProphecy|MeasurementMetadataRepositoryInterface $measurementMetadataRepository;
    private ObjectProphecy|VideoMetadataExtractor $metadataExtractor;
    private ExtractVideoMetadataHandler $sut;

    protected function _before(): void
    {
        $this->measurementUuid = Uuid::fromString(self::MEASUREMENT_UUID);
        $this->measurementRepository = $this->prophesize(MeasurementRepositoryInterface::class);
        $this->measurementMetadataRepository = $this->prophesize(MeasurementMetadataRepositoryInterface::class);
        $this->metadataExtractor = $this->prophesize(VideoMetadataExtractor::class);
        $this->sut = new ExtractVideoMetadataHandler(
            $this->prophesize(LoggerInterface::class)->reveal(),
            $this->measurementRepository->reveal(),
            $this->measurementMetadataRepository->reveal(),
            $this->metadataExtractor->reveal()
        );
    }

    public function testDoNothingWhenMeasurementCanNotBeFound(): void
    {
        $this->measurementRepository->findOneByUuid($this->measurementUuid)->willReturn(null);

        $this->invokeSut();

        $this->assertMeasurementMetadataWasNotAdded();
    }

    public function testDoNothingWhenMeasurementIsNotVideoType(): void
    {
        $this->measurementRepository->findOneByUuid($this->measurementUuid)->willReturn(
            $this->prophesize(MissionControlData::class)->reveal()
        );

        $this->invokeSut();

        $this->assertMeasurementMetadataWasNotAdded();
    }

    public function testDoNothingWhenMeasurementOriginalFileIsNull(): void
    {
        $measurement = $this->prophesize(Video::class);
        $this->measurementRepository->findOneByUuid($this->measurementUuid)->willReturn($measurement->reveal());
        $measurement->getOriginalFile()->willReturn(null);

        $this->invokeSut();

        $this->assertMeasurementMetadataWasNotAdded();
    }

    #[DataProvider('doNothingWhenMeasurementOriginalFileFilePathIsWrongDataProvider')]
    public function testDoNothingWhenMeasurementOriginalFileFilePathIsWrong(false|string $realPathValue): void
    {
        $originalFile = $this->prophesize(File::class)->willBeConstructedWith(['', false]);
        $measurement = $this->prophesize(Video::class);
        $this->measurementRepository->findOneByUuid($this->measurementUuid)->willReturn($measurement->reveal());
        $measurement->getOriginalFile()->willReturn($originalFile);
        $measurement->setMetadata(Argument::type(VideoMetadata::class))->willReturn($measurement->reveal());
        $originalFile->getRealPath()->willReturn($realPathValue);

        $this->invokeSut();

        $this->assertMeasurementMetadataWasNotAdded();
    }

    public function testExtractVideoMetadata(): void
    {
        $pathToFile = '/path/to/file.avi';
        $originalFile = $this->prophesize(File::class)->willBeConstructedWith(['', false]);
        $measurement = $this->prophesize(Video::class);
        $this->measurementRepository->findOneByUuid($this->measurementUuid)->willReturn($measurement->reveal());
        $measurement->getOriginalFile()->willReturn($originalFile);
        $measurement->setMetadata(Argument::type(VideoMetadata::class))->willReturn($measurement->reveal());
        $originalFile->getRealPath()->willReturn($pathToFile);
        $videoMetadataDto = (new VideoMetadataDto())->setDataFormat('avi');
        $this->metadataExtractor->extract($pathToFile)->willReturn($videoMetadataDto);

        $this->invokeSut();

        $this->measurementMetadataRepository->add(Argument::type(VideoMetadata::class), true)->shouldBeCalled();
    }

    private function invokeSut(): void
    {
        $this->sut->__invoke(new VideoAddedEvent($this->measurementUuid));
    }

    private function assertMeasurementMetadataWasNotAdded(): void
    {
        $this->measurementMetadataRepository->add(Argument::any(), Argument::any())->shouldNotBeCalled();
    }

    private function doNothingWhenMeasurementOriginalFileFilePathIsWrongDataProvider(): array
    {
        return [
            [false],
            [''],
        ];
    }
}
