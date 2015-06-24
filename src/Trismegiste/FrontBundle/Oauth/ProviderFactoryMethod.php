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

    /** @return \League\OAuth2\Client\Provider\AbstractProvider */
    public function create($code);
}