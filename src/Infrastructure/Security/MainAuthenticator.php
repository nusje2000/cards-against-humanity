<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Security;

use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Nusje2000\CAH\Infrastructure\Entity\User;
use Nusje2000\CAH\Infrastructure\Model\LoginCredentials;
use Nusje2000\CAH\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class MainAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly RouterInterface $router,

) {}

    public function supports(Request $request): bool
    {
        return 'cah_login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }
    public function getCredentials(Request $request): LoginCredentials
    {
        $credentials = LoginCredentials::fromRequest($request);
        $request->getSession()->set(Security::LAST_USERNAME, $credentials->username()->get());

        return $credentials;
    }

    public function authenticate(Request $request): Passport
    {
        $password = strval($request->request->get('password'));
        $credentials = LoginCredentials::fromRequest($request);

        return new Passport(
            new UserBadge($credentials->username()->toString()),
            new CustomCredentials(function(string $password, User $user) {
                return $this->passwordHasher->isPasswordValid($user, $password);
            }, $password)
        );
    }

    public function getUser(LoginCredentials $credentials): User
    {
        try {
            return $this->userRepository->byUsername($credentials->username());
        } catch (PlayerDoesNotExist $exception) {
            throw new CustomUserMessageAuthenticationException('No user found.', [
                'username' => $credentials->username(),
            ], 0, $exception);
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }
        return new RedirectResponse($this->urlGenerator->generate('cah_index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        return new RedirectResponse(
            $this->router->generate('cah_login')
        );
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('cah_index');
    }
}
