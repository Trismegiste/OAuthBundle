<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Oauth\Bridge;

use League\OAuth1\Client\Server\Twitter as LeagueTwitter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Trismegiste\OAuthBundle\Security\Token;
use Trismegiste\OAuthBundle\Oauth\ThirdPartyAuthentication;

/**
 * Twitter is a bridge over the Twitter OAuth1 provider from league/oauth1-client
 */
class Twitter implements ThirdPartyAuthentication
{

    const TEMP_CRED = 'temporary_credentials';

    /** @var LeagueTwitter */
    protected $provider;

    /** @var SessionInterface */
    protected $session;

    /** @var \Psr\Log\LoggerInterface */
    protected $logger;

    public function __construct($client, $secret, $callback, SessionInterface $sess, \Psr\Log\LoggerInterface $logger)
    {
        $this->provider = new LeagueTwitter([
            'identifier' => $client,
            'secret' => $secret,
            'callback_uri' => $callback
        ]);
        $this->session = $sess;
        $this->logger = $logger;
    }

    public function buildToken(Request $req, $firewallName)
    {
        $providerKey = $req->attributes->get('provider');
        // Retrieve the temporary credentials from step 2
        $temporaryCredentials = unserialize($this->session->get(self::TEMP_CRED));

        // Third and final part to OAuth 1.0 authentication is to retrieve token
        // credentials (formally known as access tokens in earlier OAuth 1.0
        // specs).
        $tokenCredentials = $this->provider
                ->getTokenCredentials($temporaryCredentials
                , $req->query->get('oauth_token')
                , $req->query->get('oauth_verifier'));

        $this->session->remove(self::TEMP_CRED);

        // We got an access token, let's now get the user's details
        /** @var \League\OAuth1\Client\Entity\User */
        $userDetails = $this->provider->getUserDetails($tokenCredentials);

        $internToken = new Token($firewallName, $providerKey, $userDetails->uid, [self::IDENTIFIED]);
        $internToken->setAttribute('nickname', $userDetails->nickname);
        $this->logger->debug('twitter', iterator_to_array($userDetails->getIterator()));

        return $internToken;
    }

    public function getAuthorizationUrl()
    {
        $temporaryCredentials = $this->provider->getTemporaryCredentials();
        // Store the credentials in the session.
        $this->session->set(self::TEMP_CRED, serialize($temporaryCredentials));

        // Second part of OAuth 1.0 authentication is to redirect the
        // resource owner to the login screen on the server.
        return $this->provider->getAuthorizationUrl($temporaryCredentials);
    }

    public function validateRequest(Request $req)
    {
        if (!($req->query->has('oauth_token') && $req->query->has('oauth_verifier'))) {
            throw new AuthenticationException("Invalid return");
        }
    }

}
