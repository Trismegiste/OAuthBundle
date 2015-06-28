<?php

/*
 * connect-oauth
 */

namespace Trismegiste\OAuthBundle\Oauth;

use Symfony\Component\HttpFoundation\Request;

/**
 * ExternalAuthentication is a contract for third-party authentication
 */
interface ThirdPartyAuthentication
{

    const IDENTIFIED = 'ROLE_IDENTIFIED';

    public function getAuthorizationUrl();

    public function validateRequest(Request $req);

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $req
     * 
     * @return \Trismegiste\OAuthBundle\Security\Token
     */
    public function buildToken(Request $req);
}