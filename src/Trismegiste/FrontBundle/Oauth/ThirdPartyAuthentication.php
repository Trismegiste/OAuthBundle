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

    public function getAuthorizationUrl($param);

    public function validateRequest(Request $req);
}