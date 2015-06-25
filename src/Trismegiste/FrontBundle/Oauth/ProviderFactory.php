<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Google;
use LogicException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use League\OAuth1\Client\Server\Twitter;
use League\OAuth1\Client\Server\Tumblr;

/**
 * ProviderFactory 
 */
class ProviderFactory implements ProviderFactoryMethod
{

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var CsrfProviderInterface */
    protected $csrf;

    /** @var SessionInterface */
    protected $session;

    /** var array */
    protected $providerConfig;

    public function __construct(\ArrayAccess $config, UrlGeneratorInterface $gen, CsrfProviderInterface $csrfService, \Symfony\Component\HttpFoundation\Session\SessionInterface $sess)
    {
        $this->urlGenerator = $gen;
        $this->csrf = $csrfService;
        $this->session = $sess;
        $this->providerConfig = $config['oauth'];
    }

    public function create($providerKey)
    {
        if (!array_key_exists($providerKey, $this->providerConfig)) {
            throw new \RuntimeException("$providerKey is not configured");
        }

        $cfg = $this->providerConfig[$providerKey];

        switch ($providerKey) {
            case 'github':
                return new OAuth2ProviderBridge(new Github([
                    'clientId' => $cfg['public'],
                    'clientSecret' => $cfg['secret'],
                    'redirectUri' => $this->generateLoginCheckUrl($providerKey),
                    'scopes' => [],
                        ]), $this->csrf);
                break;

            case 'google':
                return new OAuth2ProviderBridge(new Google([
                    'clientId' => $cfg['public'],
                    'clientSecret' => $cfg['secret'],
                    'redirectUri' => $this->generateLoginCheckUrl($providerKey),
                    'scopes' => ['profile'],
                        ]), $this->csrf);
                break;

            case 'twitter':
                return new OAuth1ProviderBridge(new Twitter([
                    'identifier' => $cfg['public'],
                    'secret' => $cfg['secret'],
                    'callback_uri' => $this->generateLoginCheckUrl($providerKey)
                        ]), $this->session);
                break;

            case 'tumblr':
                return new OAuth1ProviderBridge(new Tumblr([
                    'identifier' => $cfg['public'],
                    'secret' => $cfg['secret'],
                    'callback_uri' => $this->generateLoginCheckUrl($providerKey)
                        ]), $this->session);
                break;
            default:
                throw new LogicException("$providerKey is not supported");
        }
    }

    protected function generateLoginCheckUrl($providerKey)
    {
        return $this->urlGenerator
                        ->generate('trismegiste_logincheck', [
                            'provider' => $providerKey
                                ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

}