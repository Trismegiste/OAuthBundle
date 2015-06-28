<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use ArrayAccess;
use League\OAuth1\Client\Server\Tumblr;
use League\OAuth1\Client\Server\Twitter;
use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Google;
use LogicException;
use RuntimeException;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    public function __construct(ArrayAccess $config, UrlGeneratorInterface $gen, CsrfProviderInterface $csrfService, SessionInterface $sess, $debug = false)
    {
        $this->urlGenerator = $gen;
        $this->csrf = $csrfService;
        $this->session = $sess;
        $this->providerConfig = $config['oauth'];
        if ($debug) {
            $this->providerConfig['dummy'] = [];
        }
    }

    public function create($providerKey)
    {
        if (!array_key_exists($providerKey, $this->providerConfig)) {
            throw new RuntimeException("$providerKey is not configured");
        }

        $cfg = $this->providerConfig[$providerKey];
        $callback = $this->generateLoginCheckUrl($providerKey);

        switch ($providerKey) {
            case 'github':
                return new OAuth2ProviderBridge(new Github([
                    'clientId' => $cfg['public'],
                    'clientSecret' => $cfg['secret'],
                    'redirectUri' => $callback,
                    'scopes' => [],
                        ]), $this->csrf);
                break;

            case 'google':
                return new OAuth2ProviderBridge(new Google([
                    'clientId' => $cfg['public'],
                    'clientSecret' => $cfg['secret'],
                    'redirectUri' => $callback,
                    'scopes' => ['profile'],
                        ]), $this->csrf);
                break;

            case 'twitter':
                return new OAuth1ProviderBridge(new Twitter([
                    'identifier' => $cfg['public'],
                    'secret' => $cfg['secret'],
                    'callback_uri' => $callback
                        ]), $this->session);
                break;

            case 'tumblr':
                return new OAuth1ProviderBridge(new Tumblr([
                    'identifier' => $cfg['public'],
                    'secret' => $cfg['secret'],
                    'callback_uri' => $callback
                        ]), $this->session);
                break;

            case 'dummy':
                return new DummyBridge($callback, $this->urlGenerator);
                break;

            default:
                throw new LogicException("$providerKey is not supported");
        }
    }

    protected function generateLoginCheckUrl($providerKey)
    {
        return $this->urlGenerator
                        ->generate('trismegiste_oauth_check', [
                            'provider' => $providerKey
                                ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function getAvaliableProvider()
    {
        return array_keys($this->providerConfig);
    }

}