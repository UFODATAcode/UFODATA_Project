<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: Request::METHOD_POST)]
    public function login(#[CurrentUser] ?User $user, ): JsonResponse
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->json([
                'error' => 'Invalid login request: check that the Content-Type header is "application/json".'
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'user' => $user?->getId() ?? null,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): JsonResponse
    {
        throw new \Exception('should not be reached');
    }
}
