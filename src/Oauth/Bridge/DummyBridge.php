<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Oauth\Bridge;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Trismegiste\OAuthBundle\Security\Token;
use Trismegiste\OAuthBundle\Oauth\ThirdPartyAuthentication;

/**
 * Dummy is a dummy oauth bridge
 */
class Dummy implements ThirdPartyAuthentication
{

    protected $loginCheck;

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    public function __construct($loginCheck, UrlGeneratorInterface $gen)
    {
        $this->urlGenerator = $gen;
        $this->loginCheck = $loginCheck;
    }

    public function buildToken(Request $req, $firewallName)
    {
        $uid = $req->query->get('uid');
        $token = new Token($firewallName, 'dummy', $uid, [self::IDENTIFIED]);
        $token->setAttribute('nickname', $req->query->get('nickname'));

        return $token;
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
