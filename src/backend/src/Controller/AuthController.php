<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack
    ) {}

    #[Route('/auth/info')]
    public function info(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        return $user ? $this->json([
            'name' => $user->getName(),
            'wallet' => $user->getWallet()->getId(),
            'role' => $user->getRole(),
            'roles' => $user->getRoles(),
        ]) : $this->json([], Response::HTTP_UNAUTHORIZED);
    }

    #[Route('/auth/login', name: 'login')]
    public function login(): Response
    {
        return $this->info();
    }
}
