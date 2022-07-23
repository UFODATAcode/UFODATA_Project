<?php

namespace App\Controller;

use App\Command\AddObservationCommand;
use App\Command\DeleteObservationCommand;
use App\Command\UpdateObservationCommand;
use App\Handler\AddObservationHandler;
use App\Handler\DeleteObservationHandler;
use App\Handler\GetObservationsHandler;
use App\Handler\UpdateObservationHandler;
use App\Query\GetObservationsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/observations')]
class ObservationController extends AbstractController
{
    public function __construct(
        private readonly AddObservationHandler $addObservationHandler,
        private readonly DeleteObservationHandler $deleteObservationHandler,
        private readonly GetObservationsHandler $getObservationsHandler,
        private readonly UpdateObservationHandler $updateObservationHandler,
    ) {}

    #[Route(name: 'add_observation', methods: Request::METHOD_POST)]
    public function addObservation(AddObservationCommand $command): Response
    {
        $this->addObservationHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{uuid}', name: 'delete_observation', methods: Request::METHOD_DELETE)]
    public function deleteObservation(DeleteObservationCommand $command): Response
    {
        $this->deleteObservationHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{uuid}', name: 'update_observation', methods: Request::METHOD_PATCH)]
    public function updateObservation(UpdateObservationCommand $command): Response
    {
        $this->updateObservationHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(name: 'get_observations', methods: Request::METHOD_GET)]
    public function getObservations(GetObservationsQuery $query): JsonResponse
    {
        return new JsonResponse($this->getObservationsHandler->__invoke($query));
    }
}
