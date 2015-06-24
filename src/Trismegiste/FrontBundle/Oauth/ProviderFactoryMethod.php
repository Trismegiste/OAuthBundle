<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

/**
 * ProviderFactoryMethod is a contract for a factory method of provider
 */
interface ProviderFactoryMethod
{

    /** @return ThirdPartyAuthentication */
    public function create($code);
}