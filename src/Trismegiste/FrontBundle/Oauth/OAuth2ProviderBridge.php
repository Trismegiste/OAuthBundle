<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use League\OAuth2\Client\Provider\AbstractProvider;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * OAuth2ProviderBridge is a bridge to enapsulate Provider from OAuth2-client
 */
class OAuth2ProviderBridge implements ThirdPartyAuthentication
{

    /** @var AbstractProvider */
    protected $provider;

    /** @var SessionInterface */
    protected $session;

    /** @var CsrfProviderInterface */
    protected $csrf;

    public function __construct(AbstractProvider $pro, SessionInterface $sess, CsrfProviderInterface $csrfService)
    {
        $this->provider = $pro;
        $this->session = $sess;
        $this->csrf = $csrfService;
    }

    public function getAuthorizationUrl()
    {
        $options = ['state' => $this->csrf->generateCsrfToken(__CLASS__)];

        return $this->provider->getAuthorizationUrl($options);
    }

}