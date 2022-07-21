<?php

namespace App\Controller;

use App\Command\AddMeasurementCommand;
use App\Command\UpdateMeasurementCommand;
use App\Handler\AddMeasurementHandler;
use App\Handler\UpdateMeasurementHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/measurements')]
class MeasurementController extends AbstractController
{
    public function __construct(
        private readonly AddMeasurementHandler $addMeasurementHandler,
        private readonly UpdateMeasurementHandler $updateMeasurementHandler,
    ) {}

    #[Route(name: 'add_measurement', methods: Request::METHOD_POST)]
    public function addMeasurement(AddMeasurementCommand $command): Response
    {
        $this->addMeasurementHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{uuid}', name: 'update_measurement', methods: Request::METHOD_PATCH)]
    public function updateMeasurement(UpdateMeasurementCommand $command): Response
    {
        $this->updateMeasurementHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
