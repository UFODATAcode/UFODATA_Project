<?php

namespace App\Controller;

use App\Command\AddObservationCommand;
use App\Command\DeleteObservationCommand;
use App\Entity\User;
use App\Handler\AddObservationCommandHandler;
use App\Handler\DeleteObservationCommandHandler;
use App\Handler\GetObservationsQueryHandler;
use App\Query\GetObservationsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/observations')]
class ObservationController extends AbstractController
{
    public function __construct(
        private readonly AddObservationCommandHandler $addObservationCommandHandler,
        private readonly DeleteObservationCommandHandler $deleteObservationCommandHandler,
        private readonly GetObservationsQueryHandler $getObservationsQueryHandler,
    ) {}

    #[Route(name: 'add_observation', methods: Request::METHOD_POST)]
    public function addObservation(AddObservationCommand $command, #[CurrentUser] User $user): Response
    {
        $this->addObservationCommandHandler->__invoke($command, $user);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{uuid}', name: 'delete_observation', methods: Request::METHOD_DELETE)]
    public function deleteObservation(DeleteObservationCommand $command, #[CurrentUser] User $user): Response
    {
        $this->deleteObservationCommandHandler->__invoke($command, $user);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(name: 'get_observations', methods: Request::METHOD_GET)]
    public function getObservations(GetObservationsQuery $query): JsonResponse
    {
        return new JsonResponse($this->getObservationsQueryHandler->__invoke($query));
    }
}
