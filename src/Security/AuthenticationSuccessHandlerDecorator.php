<?php

namespace App\Security;

use App\Contract\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\MapDecorated;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

#[AsDecorator(decorates: 'lexik_jwt_authentication.handler.authentication_success')]
class AuthenticationSuccessHandlerDecorator implements AuthenticationSuccessHandlerInterface
{
    private readonly AuthenticationSuccessHandler $inner;

    public function __construct(#[MapDecorated] $inner)
    {
        $this->inner = $inner;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();
        if ($user instanceof UserInterface && !$user->isActive()) {
            return new JsonResponse(
                [
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Invalid credentials.',
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $this->inner->onAuthenticationSuccess($request, $token);
    }
}
