<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use League\OAuth2\Client\Provider\AbstractProvider;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * ProviderBridge
 */
class ProviderBridge
{

    public function __construct(AbstractProvider $pro, SessionInterface $sess)
    {
        
    }

}