<?php

/*
 * connect-oauth
 */

namespace Trismegiste\OAuthBundle\Oauth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Trismegiste\OAuthBundle\Security\Token;

/**
 * DummyBridge is a dummy oauth bridge
 */
class DummyBridge implements ThirdPartyAuthentication
{

    protected $loginCheck;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    public function __construct($loginCheck, UrlGeneratorInterface $gen)
    {
        $this->urlGenerator = $gen;
        $this->loginCheck = $loginCheck;
    }

    public function buildToken(Request $req)
    {
        return new Token('dummy', rand(), [self::IDENTIFIED]);
    }

    public function getAuthorizationUrl()
    {
        return $this->urlGenerator->generate('trismegiste_oauth_dummyserver', ['redirect' => $this->loginCheck]);
    }

    public function validateRequest(Request $req)
    {
        // do nothing   
    }

}