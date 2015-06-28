<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Trismegiste\FrontBundle\Security\Token;

/**
 * DummyBridge is a dummy oauth bridge
 */
class DummyBridge implements ThirdPartyAuthentication
{

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    public function __construct(UrlGeneratorInterface $gen)
    {
        $this->urlGenerator = $gen;
    }

    public function buildToken(Request $req)
    {
        return new Token('dummy', rand(), [self::IDENTIFIED]);
    }

    public function getAuthorizationUrl()
    {
        $redirect = $this->urlGenerator
                ->generate('trismegiste_logincheck', ['provider' => 'dummy'], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->urlGenerator->generate('trismegiste_oauth_dummy', ['redirect' => $redirect]);
    }

    public function validateRequest(Request $req)
    {
        // do nothing   
    }

}