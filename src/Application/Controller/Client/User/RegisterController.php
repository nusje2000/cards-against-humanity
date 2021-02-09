<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Application\Controller\Client\User;

use League\Tactician\CommandBus;
use Nusje2000\CAH\Infrastructure\Form\Model\Registration;
use Nusje2000\CAH\Infrastructure\Form\Type\RegistrationType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class RegisterController
{
    private FormFactory $formFactory;

    private UrlGeneratorInterface $urlGenerator;

    private CommandBus $commandBus;

    private Environment $twig;

    public function __construct(FormFactory $formFactory, UrlGeneratorInterface $urlGenerator, CommandBus $commandBus, Environment $environment)
    {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->commandBus = $commandBus;
        $this->twig = $environment;
    }

    public function __invoke(Request $request): Response
    {
        $form = $this->formFactory->create(RegistrationType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Registration $registration */
            $registration = $form->getData();
            $command = $registration->command();

            $this->commandBus->handle($command);

            return new RedirectResponse($this->urlGenerator->generate('cah_login'));
        }

        return new Response(
            $this->twig->render('user/registration.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }
}
