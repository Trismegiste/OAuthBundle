<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use League\OAuth2\Client\Provider\Github;
use LogicException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * ProviderFactory 
 */
class ProviderFactory
{

    protected $urlGenerator;

    public function __construct(UrlGeneratorInterface $gen)
    {
        $this->urlGenerator = $gen;
    }

    public function create($providerKey)
    {
        switch ($providerKey) {
            case 'github':
                return new Github([
                    'clientId' => '51a83ff9f1216abd83ee',
                    'clientSecret' => '36a8f497751ba31321ad47fbba68fc42eb24bf8e',
                    'redirectUri' => $this->urlGenerator
                            ->generate('trismegiste_logincheck', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'scopes' => [],
                ]);
                break;

            default:
                throw new LogicException("$providerKey is not supported");
        }
    }

}