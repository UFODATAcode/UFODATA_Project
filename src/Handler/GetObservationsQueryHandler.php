<?php

namespace App\Handler;

use App\Contract\ObservationRepositoryInterface;
use App\Entity\Observation;
use App\Query\GetObservationsQuery;
use App\ValueObject\Pagination;
use App\Response\GetResourcesResponse;
use App\Response\ObservationResponse as ResponseModel;

class GetObservationsQueryHandler
{
    public function __construct(
        private readonly ObservationRepositoryInterface $observationRepository
    ) {}

    public function __invoke(GetObservationsQuery $query): GetResourcesResponse
    {
        $pagination = $query->pagination ?? new Pagination();
        $observations = \array_map(
            fn(Observation $observation) => new ResponseModel($observation->getUuid(), $observation->getName()),
            $this->observationRepository->findAllForList($pagination)
        );

        return new GetResourcesResponse($observations, $pagination);
    }
}
