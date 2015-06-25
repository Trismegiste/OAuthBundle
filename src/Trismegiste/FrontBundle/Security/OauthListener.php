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

    /**
     * Ctor
     * 
     * @param SecurityContextInterface $securityContext
     * @param AuthenticationManagerInterface $authenticationManager
     * @param SessionAuthenticationStrategyInterface $sessionStrategy
     * @param HttpUtils $httpUtils
     * @param type $providerKey
     * @param AuthenticationSuccessHandlerInterface $successHandler
     * @param AuthenticationFailureHandlerInterface $failureHandler
     * @param array $options
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $dispatcher
     * @param ProviderFactoryMethod $factory
     */
    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, SessionAuthenticationStrategyInterface $sessionStrategy, HttpUtils $httpUtils, $providerKey, AuthenticationSuccessHandlerInterface $successHandler, AuthenticationFailureHandlerInterface $failureHandler, array $options = array(), LoggerInterface $logger = null, EventDispatcherInterface $dispatcher = null, ProviderFactoryMethod $factory)
    {
        parent::__construct($securityContext, $authenticationManager, $sessionStrategy, $httpUtils, $providerKey, $successHandler, $failureHandler, $options, $logger, $dispatcher);
        $this->providerFactory = $factory;
    }

    /**
     * Reminder : this method is called by parent::handle() if parent::requiresAuthentication() is true
     * in other words if 'check_path' option is matched with the curent Request (default behavior).
     * You must define a valid route, even if no controller is defined because
     * the router will kick the request out if the route does not exist in the first
     * place
     */
    protected function attemptAuthentication(Request $request)
    {
        $this->handleOAuthError($request);
        $providerKey = $this->getProviderKey($request);
        $provider = $this->providerFactory->create($providerKey);
        $provider->validateRequest($request);

        try {
            $identifiedToken = $provider->buildToken($request);
            return $this->authenticationManager->authenticate($identifiedToken);
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
     * 
     * Note: Copy-pasted from HwiOAuthBundle
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

    protected function getProviderKey(Request $req)
    {
        return $req->attributes->get('provider');
    }

}