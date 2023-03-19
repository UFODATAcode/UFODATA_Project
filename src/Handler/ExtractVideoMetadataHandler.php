<?php

namespace App\Handler;

use App\Contract\MeasurementMetadataRepositoryInterface;
use App\Contract\MeasurementRepositoryInterface;
use App\Entity\Video;
use App\Event\VideoAddedEvent;
use App\Factory\VideoMetadataFactory;
use App\Service\VideoMetadataExtractor;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ExtractVideoMetadataHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly MeasurementRepositoryInterface $measurementRepository,
        private readonly MeasurementMetadataRepositoryInterface $measurementMetadataRepository,
        private readonly VideoMetadataExtractor $metadataExtractor,
    ) {}

    public function __invoke(VideoAddedEvent $event): void
    {
        $measurementUuid = $event->getMeasurementUuid();
        $measurement = $this->measurementRepository->findOneByUuid($measurementUuid);

        if ($measurement === null) {
            $this->logger->error(
                'Measurement with UUID {uuid} not found in the DB. Can not extract video metadata.',
                ['uuid' => $measurementUuid]
            );
            return;
        }

        if (!$measurement instanceof Video) {
            $this->logger->error(
                'Measurement with UUID {uuid} is not a video. Can not extract video metadata.',
                ['uuid' => $measurementUuid]
            );
            return;
        }

        $measurementFile = $measurement->getOriginalFile();

        if ($measurementFile === null) {
            $this->logger->error(
                'Measurement with UUID {uuid} has no file attached. Can not extract video metadata.',
                ['uuid' => $measurementUuid]
            );
            return;
        }

        $measurementFilePath = $measurementFile->getRealPath();

        if ($measurementFilePath === false || $measurementFilePath === '') {
            $this->logger->error(
                'Can not get absolute file path for measurement with UUID {uuid}. Can not extract video metadata.',
                ['uuid' => $measurementUuid]
            );
            return;
        }

        $metadata = VideoMetadataFactory::fromDto($this->metadataExtractor->extract($measurementFilePath));
        $this->measurementMetadataRepository->add($metadata, true);

        $this->logger->info(
            'Successfully extracted video metadata from measurement with UUID {}.',
            ['uuid' => $measurementUuid]
        );
    }
}