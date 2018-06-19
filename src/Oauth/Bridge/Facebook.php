<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Oauth\Bridge;

use League\OAuth2\Client\Provider\Facebook as LeagueFacebook;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Trismegiste\OAuthBundle\Oauth\ThirdPartyAuthentication;
use Trismegiste\OAuthBundle\Security\Token;

/**
 * Facebook is a bridge to enapsulate Facebook Provider from OAuth2-client
 */
class Facebook implements ThirdPartyAuthentication
{

    const STATE_KEY = 'state';

    /** @var LeagueFacebook */
    protected $provider;

    /** @var CsrfProviderInterface */
    protected $csrf;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct($client, $secret, $callback, CsrfProviderInterface $csrfService, LoggerInterface $logger)
    {
        $this->provider = new LeagueFacebook([
            'clientId' => $client,
            'clientSecret' => $secret,
            'redirectUri' => $callback,
            'graphApiVersion'   => 'v2.10'
            ]);
        $this->csrf = $csrfService;
        $this->logger = $logger;
    }

    public function getAuthorizationUrl()
    {
        $options = [self::STATE_KEY => $this->csrf->generateCsrfToken(__CLASS__)];

        return $this->provider->getAuthorizationUrl($options);
    }

    public function validateRequest(Request $req)
    {
        if (!$this->csrf->isCsrfTokenValid(__CLASS__, $req->query->get(self::STATE_KEY, ''))) {
            throw new AuthenticationException("Invalid state");
        }
    }

    public function buildToken(Request $req, $firewallName)
    {
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $req->query->get('code')
        ]);
        $providerKey = $req->attributes->get('provider');

        // We got an access token, let's now get the user's details
        /** @var \League\OAuth2\Client\Provider\FacebookUser */
        $userDetails = $this->provider->getResourceOwner($token);
        $internToken = new Token($firewallName, $providerKey, $userDetails->getId(), [self::IDENTIFIED]);
        $internToken->setAttribute('nickname', $userDetails->getName());
        $internToken->setAttribute('gender', ($userDetails->getGender() == 'male') ? 'xy' : 'xx' );
        $this->logger->debug('facebook', $userDetails->toArray());

        return $internToken;
    }

}
