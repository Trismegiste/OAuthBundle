<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\Security\Http\Session\SessionAuthenticationStrategyInterface;
use Trismegiste\FrontBundle\Oauth\ProviderFactoryMethod;

/**
 * OauthListener is a firewall listener
 */
class OauthListener extends AbstractAuthenticationListener
{

    /** @var ProviderFactoryMethod */
    protected $providerFactory;

    public function __construct(ProviderFactoryMethod $factory, SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, SessionAuthenticationStrategyInterface $sessionStrategy, HttpUtils $httpUtils, $providerKey, AuthenticationSuccessHandlerInterface $successHandler, AuthenticationFailureHandlerInterface $failureHandler, array $options = array(), LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null)
    {
        $this->providerFactory = $factory;
        parent::__construct($securityContext, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, $options, $logger, $dispatcher);
    }

    protected function attemptAuthentication(Request $request)
    {
        $this->handleOAuthError($request);
        $provider = $this->providerFactory->create('github'); // @todo with a bridge

        if (!$request->query->has('state') || ($_SESSION['state'] !== $request->query->get('state'))) { // bridge
            throw new AuthenticationException("Invalid state");
        }

        try {
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $request->query->get('code')  // @todo Bridge for Provider
            ]);
            // We got an access token, let's now get the user's details
            $userDetails = $provider->getUserDetails($token);
            $identifiedToken = new \Trismegiste\FrontBundle\Security\Token('github', $token, ['ROLE_IDENTIFIED']);
            $identifiedToken->setUserInfo($userDetails); // for registration

            return $this->authenticationManager->authenticate($token);
        } catch (Exception $e) {
            throw new AuthenticationException("Failed to validate the oauth token");
        }
    }

    /**
     * Detects errors returned by resource owners and transform them into
     * human readable messages
     *
     * @param Request $request
     *
     * @throws AuthenticationException
     */
    private function handleOAuthError(Request $request)
    {
        $error = null;

        // Try to parse content if error was not in request query
        if ($request->query->has('error') || $request->query->has('error_code')) {
            if ($request->query->has('error_description') || $request->query->has('error_message')) {
                throw new AuthenticationException(rawurldecode($request->query->get('error_description', $request->query->get('error_message'))));
            }

            $content = json_decode($request->getContent(), true);
            if (JSON_ERROR_NONE === json_last_error() && isset($content['error'])) {
                if (isset($content['error']['message'])) {
                    throw new AuthenticationException($content['error']['message']);
                }

                if (isset($content['error']['code'])) {
                    $error = $content['error']['code'];
                } elseif (isset($content['error']['error-code'])) {
                    $error = $content['error']['error-code'];
                } else {
                    $error = $request->query->get('error');
                }
            }
        } elseif ($request->query->has('oauth_problem')) {
            $error = $request->query->get('oauth_problem');
        }

        if (null !== $error) {
            throw new AuthenticationException($this->transformOAuthError($error));
        }
    }

    /**
     * Transforms OAuth error codes into human readable format
     *
     * @param string $errorCode
     *
     * @return string
     */
    private function transformOAuthError($errorCode)
    {
        // "translate" error to human readable format
        switch ($errorCode) {
            case 'access_denied':
                return 'You have refused access for this site.';

            case 'authorization_expired':
                return 'Authorization expired.';

            case 'bad_verification_code':
                return 'Bad verification code.';

            case 'consumer_key_rejected':
                return 'You have refused access for this site.';

            case 'incorrect_client_credentials':
                return 'Incorrect client credentials.';

            case 'invalid_assertion':
                return 'Invalid assertion.';

            case 'redirect_uri_mismatch':
                return 'Redirect URI mismatches configured one.';

            case 'unauthorized_client':
                return 'Unauthorized client.';

            case 'unknown_format':
                return 'Unknown format.';
        }

        return sprintf('Unknown OAuth error: "%s".', $errorCode);
    }

}