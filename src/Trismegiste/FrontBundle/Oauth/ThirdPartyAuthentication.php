<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use Symfony\Component\HttpFoundation\Request;

/**
 * ExternalAuthentication is a contract for third-party authentication
 */
interface ThirdPartyAuthentication
{

    public function getAuthorizationUrl();

    public function validateRequest(Request $req);

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $req
     * 
     * @return \Trismegiste\FrontBundle\Security\Token
     */
    public function buildToken(Request $req);
}