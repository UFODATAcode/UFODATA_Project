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
use App\Response\ObservationResponse;
use App\ValueObject\Pagination;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
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
    #[OA\RequestBody(
        content: new OA\JsonContent(
            ref: new Model(type: AddObservationCommand::class),
        )
    )]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Successfully obtained the command.')]
    public function addObservation(AddObservationCommand $command): Response
    {
        $this->addObservationHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{uuid}', name: 'delete_observation', methods: Request::METHOD_DELETE)]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Successfully obtained the command.')]
    public function deleteObservation(DeleteObservationCommand $command): Response
    {
        $this->deleteObservationHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{uuid}', name: 'update_observation', methods: Request::METHOD_PATCH)]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            ref: new Model(type: UpdateObservationCommand::class),
        )
    )]
    #[OA\Response(response: Response::HTTP_NO_CONTENT, description: 'Successfully obtained the command.')]
    public function updateObservation(UpdateObservationCommand $command): Response
    {
        $this->updateObservationHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(name: 'get_observations', methods: Request::METHOD_GET)]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Returns list of observations.',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: 'data',
                    type: 'array',
                    items: new OA\Items(new Model(type: ObservationResponse::class)),
                ),
                new OA\Property(
                    property: 'pagination',
                    ref: new Model(type: Pagination::class),
                ),
            ],
            type: 'object',
        ),
    )]
    public function getObservations(GetObservationsQuery $query): JsonResponse
    {
        return new JsonResponse($this->getObservationsHandler->__invoke($query));
    }
}
