<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Oauth;

use League\OAuth2\Client\Provider\AbstractProvider;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Trismegiste\OAuthBundle\Security\Token;

/**
 * OAuth2ProviderBridge is a bridge to enapsulate Provider from OAuth2-client
 */
class OAuth2ProviderBridge implements ThirdPartyAuthentication
{

    const STATE_KEY = 'state';

    /** @var AbstractProvider */
    protected $provider;

    /** @var CsrfProviderInterface */
    protected $csrf;

    public function __construct(AbstractProvider $pro, CsrfProviderInterface $csrfService)
    {
        $this->provider = $pro;
        $this->csrf = $csrfService;
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
        /** @var \League\OAuth2\Client\Entity\User */
        $userDetails = $this->provider->getUserDetails($token);
        $internToken = new Token($firewallName, $providerKey, $userDetails->uid, [self::IDENTIFIED]);
        $internToken->setAttribute('nickname', $userDetails->nickname);

        return $internToken;
    }

}