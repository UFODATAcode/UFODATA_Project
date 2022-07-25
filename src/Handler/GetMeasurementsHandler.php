<?php

namespace App\Handler;

use App\Contract\GetMeasurementsQueryInterface;
use App\Contract\MeasurementRepositoryInterface;
use App\Entity\Measurement;
use App\Response\ObservationResponse;
use App\Response\ProviderResponse;
use App\ValueObject\Pagination;
use App\Response\GetResourcesResponse;
use App\Response\MeasurementResponse;

class GetMeasurementsHandler
{
    public function __construct(
        private readonly MeasurementRepositoryInterface $measurementRepository,
    ) {}

    public function __invoke(GetMeasurementsQueryInterface $query): GetResourcesResponse
    {
        $pagination = $query->pagination ?? new Pagination();
        $observations = \array_map(
            fn(Measurement $measurement) => new MeasurementResponse(
                $measurement->getUuid(),
                $measurement->getName(),
                $measurement->getType(),
                new ObservationResponse(
                    $measurement->getObservation()->getUuid(),
                    $measurement->getObservation()->getName(),
                ),
                new ProviderResponse(
                    $measurement->getProvider()->getUuid(),
                    $measurement->getProvider()->getName(),
                ),
            ),
            $this->measurementRepository->findAllForList($pagination)
        );

        return new GetResourcesResponse($observations, $pagination);
    }
}
