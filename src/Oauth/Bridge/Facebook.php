<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Oauth\Bridge;

use League\OAuth2\Client\Provider\Facebook as LeagueFacebook;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Trismegiste\OAuthBundle\Security\Token;
use Trismegiste\OAuthBundle\Oauth\ThirdPartyAuthentication;

/**
 * Facebook is a bridge to enapsulate Facebook Provider from OAuth2-client
 */
class Facebook implements ThirdPartyAuthentication {

    const STATE_KEY = 'state';

    /** @var LeagueFacebook */
    protected $provider;

    /** @var CsrfProviderInterface */
    protected $csrf;

    public function __construct($client, $secret, $callback, CsrfProviderInterface $csrfService) {
        $this->provider = new LeagueFacebook([
            'clientId' => $client,
            'clientSecret' => $secret,
            'redirectUri' => $callback,
            'scopes' => ['public_profile'],
        ]);
        $this->csrf = $csrfService;
    }

    public function getAuthorizationUrl() {
        $options = [self::STATE_KEY => $this->csrf->generateCsrfToken(__CLASS__)];

        return $this->provider->getAuthorizationUrl($options);
    }

    public function validateRequest(Request $req) {
        if (!$this->csrf->isCsrfTokenValid(__CLASS__, $req->query->get(self::STATE_KEY, ''))) {
            throw new AuthenticationException("Invalid state");
        }
    }

    public function buildToken(Request $req, $firewallName) {
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