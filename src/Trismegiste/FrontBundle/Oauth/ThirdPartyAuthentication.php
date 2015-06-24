<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

/**
 * ExternalAuthentication is a contract for third-party authentication
 */
interface ThirdPartyAuthentication
{

    public function getAuthorizationUrl($param);
}