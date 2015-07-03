<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Oauth;

use LogicException;
use RuntimeException;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Trismegiste\OAuthBundle\DependencyInjection\ProviderConfigInterface;

/**
 * ProviderFactory
 */
class ProviderFactory implements ProviderFactoryMethod
{

    const DUMMY_PROVIDER = 'dummy';

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var CsrfProviderInterface */
    protected $csrf;

    /** @var SessionInterface */
    protected $session;

    /** var array */
    protected $providerConfig;

    public function __construct(ProviderConfigInterface $config, UrlGeneratorInterface $gen, CsrfProviderInterface $csrfService, SessionInterface $sess, $debug = false)
    {
        $this->urlGenerator = $gen;
        $this->csrf = $csrfService;
        $this->session = $sess;
        $this->providerConfig = $config->all();
        if ($debug) {
            $this->providerConfig[self::DUMMY_PROVIDER] = [];
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
            case 'facebook':
                return new Bridge\Facebook($cfg['client_id'], $cfg['secret_id'], $callback, $this->csrf);
                break;

            case 'twitter':
                return new Bridge\Twitter($cfg['client_id'], $cfg['secret_id'], $callback, $this->session);
                break;

            case self::DUMMY_PROVIDER:
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
