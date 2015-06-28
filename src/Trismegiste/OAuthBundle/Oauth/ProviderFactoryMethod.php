<?php

/*
 * connect-oauth
 */

namespace Trismegiste\OAuthBundle\Oauth;

/**
 * ProviderFactoryMethod is a contract for a factory method of provider
 */
interface ProviderFactoryMethod
{

    /** @return ThirdPartyAuthentication */
    public function create($code);
}