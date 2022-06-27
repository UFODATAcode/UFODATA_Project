<?php

namespace App\Handler;

use App\Entity\Observation;
use App\Query\GetObservationsQuery;
use App\ValueObject\Pagination;
use App\Repository\ObservationRepository;
use App\Response\GetObservationsResponse;
use App\Response\ObservationResponse as ResponseModel;

class GetObservationsQueryHandler
{
    public function __construct(
        private readonly ObservationRepository $observationRepository
    ) {}

    public function __invoke(GetObservationsQuery $query): GetObservationsResponse
    {
        $pagination = $query->pagination ?? new Pagination();
        $observations = \array_map(
            fn(Observation $observation) => new ResponseModel($observation->getUuid(), $observation->getName()),
            $this->observationRepository->findAllForList($pagination)
        );

        return new GetObservationsResponse($observations, $pagination);
    }
}
