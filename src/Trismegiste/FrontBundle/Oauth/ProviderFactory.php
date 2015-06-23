<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

/**
 * ProviderFactory 
 */
class ProviderFactory
{protected $urlGenerator;
    
    public function __construct(UrlGene)
    {
        ;
    }


    public function create($providerKey)
    {
        switch ($providerKey) {
            case 'github':
                new League\OAuth2\Client\Provider\Github([
                    'clientId' => 'XXXXXXXX',
                    'clientSecret' => 'XXXXXXXX',
                    'redirectUri' => 'https://your-registered-redirect-uri/',
                    'scopes' => ['email', '...', '...'],
                ]);
                break;

            default:
                throw new \LogicException("$providerKey is not supported");
        }
    }

}