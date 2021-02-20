<?php

declare(strict_types=1);

namespace Nusje2000\CAH\Infrastructure\Security;

use InvalidArgumentException;
use Nusje2000\CAH\Domain\Exception\Game\PlayerDoesNotExist;
use Nusje2000\CAH\Infrastructure\Model\LoginCredentials;
use Nusje2000\CAH\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

final class MainAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private UserRepository $userRepository;

    private UrlGeneratorInterface $urlGenerator;

    private CsrfTokenManagerInterface $csrfTokenManager;

    private UserPasswordEncoderInterface $encoder;

    public function __construct(
        UserRepository $userRepository,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->encoder = $encoder;
    }

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

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$credentials instanceof LoginCredentials) {
            throw new InvalidArgumentException(sprintf('Expected credentials to be an instance of "%s".', LoginCredentials::class));
        }

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

        $this->validateCsrfToken($credentials);

        return $this->encoder->isPasswordValid($user, $credentials->password());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('cah_index'));
    }

    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('cah_login');
    }

    private function validateCsrfToken(LoginCredentials $credentials): void
    {
        $token = new CsrfToken('authenticate', $credentials->csrfToken());
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('Invalid csrf token.');
        }
    }
}
