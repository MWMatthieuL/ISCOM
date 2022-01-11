<?php

namespace App\Security;

use App\Entity\Subscription;
use App\Entity\SubscriptionClient;
use App\Entity\User;
use App\Form\SubscriptionType;
use App\Repository\SubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
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

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param RouterInterface $router
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        RouterInterface $router,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return object|UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('L\'adresse email n\'a pas été trouvée.');
        }

        if (null !== $user->getClient()) {
            if (false === $user->getClient()->getEnable()) {
                throw new CustomUserMessageAuthenticationException('Le compte client associé à ce compte n\'est pas activé.');
            }
        }

        if (false === $user->getEnable()) {
            throw new CustomUserMessageAuthenticationException('Le compte utilisateur n\'est pas activé.');
        }

        $rule = $this->entityManager->getRepository(Subscription::class)->findOneBy(['id' =>1]);
        if (null !== $rule) {
            if ($rule->getDisableClientAccountEndDateSubscription()) {
                $client = $user->getClient();
                if (null === $this->entityManager->getRepository(SubscriptionClient::class)->findOneBy(['client' => $client, 'enable' => true])) {
                    throw new CustomUserMessageAuthenticationException('Le compte client n\'a pas d\'abonnement valide.');
                }
            }
        } else {
            $subscription = new Subscription();
            $subscription->setDisableClientAccountEndDateSubscription(true);
            $subscription->setSubscriptionDuration("1");
            $subscription->setEnableRelaunchClientEndSubscription(true);
            $subscription->setNbDaysBeforeRelaunchClient("1");
            $subscription->setEnableRelaunchMeBeforeClientEndDate(true);
            $subscription->setNbDaysBeforeRelaunchMe("1");
            $this->entityManager->persist($subscription);
            $this->entityManager->flush();
        }

        return $user;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // Check the user's password or other credentials and return true or false
        // If there are no credentials to check, you can just return true
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): RedirectResponse
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        if (in_array('ROLE_SUPERADMIN', $token->getRoleNames()) || in_array('ROLE_ADMIN', $token->getRoleNames()) || in_array('ROLE_RESPONSABLE', $token->getRoleNames())) {
            $idClient = $token->getUser()->getClient()->getId();

            return new RedirectResponse($this->router->generate('admin_app_client_edit', ['id' => $idClient]));
        }

        return new RedirectResponse($this->router->generate('app_login'));
    }

    /**
     * @return string
     */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
