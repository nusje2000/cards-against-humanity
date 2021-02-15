<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Client;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class IndexController
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(): Response
    {
        return new Response(
            $this->twig->render('index.html.twig')
        );
    }
}
