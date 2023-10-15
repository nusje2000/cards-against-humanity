<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Security;

use InvalidArgumentException;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Nusje2000\CAH\Infrastructure\Entity\User;
use Nusje2000\CAH\Infrastructure\Model\LoginCredentials;
use Nusje2000\CAH\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class MainAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly CsrfTokenManagerInterface $csrfTokenManager,
        private readonly UserPasswordHasherInterface $passwordHasher

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

    public function authenticate(Request $request)
    {
        // TODO: Implement authenticate() method.
    }

    public function getUser(LoginCredentials $credentials, UserProviderInterface $userProvider): User
    {
        try {
            return $this->userRepository->byUsername($credentials->username());
        } catch (PlayerDoesNotExist $exception) {
            throw new CustomUserMessageAuthenticationException('No user found.', [
                'username' => $credentials->username(),
            ], 0, $exception);
        }
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        if (!$credentials instanceof LoginCredentials) {
            throw new InvalidArgumentException(sprintf('Expected credentials to be an instance of "%s".', LoginCredentials::class));
        }

        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            throw new InvalidArgumentException(sprintf('Expected user to be an instance of "%s".', PasswordAuthenticatedUserInterface::class));
        }

        $this->validateCsrfToken($credentials);

        return $this->passwordHasher->isPasswordValid($user, $credentials->password());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('cah_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('cah_index');
    }

    private function validateCsrfToken(LoginCredentials $credentials): void
    {
        $token = new CsrfToken('authenticate', $credentials->csrfToken());
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Invalid csrf token.');
        }
    }
}
