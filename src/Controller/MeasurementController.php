<?php

namespace App\Controller;

use App\Command\AddMeasurementCommand;
use App\Handler\AddMeasurementHandler;
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
    ) {}

    #[Route(name: 'add_measurement', methods: Request::METHOD_POST)]
    public function addMeasurement(AddMeasurementCommand $command): Response
    {
        $this->addMeasurementHandler->__invoke($command);
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
