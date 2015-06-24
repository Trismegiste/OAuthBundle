<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use League\OAuth2\Client\Provider\Github;
use LogicException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;

/**
 * ProviderFactory 
 */
class ProviderFactory implements ProviderFactoryMethod
{

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var CsrfProviderInterface */
    protected $csrf;

    public function __construct(UrlGeneratorInterface $gen, CsrfProviderInterface $csrfService)
    {
        $this->urlGenerator = $gen;
        $this->csrf = $csrfService;
    }

    public function create($providerKey)
    {
        switch ($providerKey) {
            case 'github':
                return new OAuth2ProviderBridge(new Github([
                    'clientId' => '51a83ff9f1216abd83ee',
                    'clientSecret' => '36a8f497751ba31321ad47fbba68fc42eb24bf8e',
                    'redirectUri' => $this->urlGenerator
                            ->generate('trismegiste_logincheck', [], UrlGeneratorInterface::ABSOLUTE_URL),
                    'scopes' => [],
                        ]), $this->csrf);
                break;

            default:
                throw new LogicException("$providerKey is not supported");
        }
    }

}