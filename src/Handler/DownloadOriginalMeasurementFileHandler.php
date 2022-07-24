<?php

namespace App\Handler;

use App\Contract\DownloadOriginalMeasurementFileQueryInterface;
use App\Contract\MeasurementRepositoryInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Vich\UploaderBundle\Handler\DownloadHandler;

class DownloadOriginalMeasurementFileHandler
{
    public function __construct(
        private readonly MeasurementRepositoryInterface $measurementRepository,
        private readonly DownloadHandler $downloadHandler,
    ) {}

    public function __invoke(DownloadOriginalMeasurementFileQueryInterface $query): StreamedResponse
    {
        return $this->downloadHandler->downloadObject(
            $this->measurementRepository->findOneByUuid($query->getUuid()),
            field: 'originalFile',
        );
    }
}
