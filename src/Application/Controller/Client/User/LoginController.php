<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Client\User;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Environment;

final class LoginController
{
    private AuthenticationUtils $authenticationUtils;

    private Environment $twig;

    public function __construct(AuthenticationUtils $authenticationUtils, Environment $twig)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->twig = $twig;
    }

    public function __invoke(): Response
    {
        return new Response($this->twig->render('user/login.html.twig', [
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ]));
    }
}
