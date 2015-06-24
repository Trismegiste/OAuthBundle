<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Oauth;

use League\OAuth2\Client\Provider\AbstractProvider;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * OAuth2ProviderBridge is a bridge to enapsulate Provider from OAuth2-client
 */
class OAuth2ProviderBridge implements ThirdPartyAuthentication
{

    const STATE_KEY = 'state';

    /** @var AbstractProvider */
    protected $provider;

    /** @var CsrfProviderInterface */
    protected $csrf;

    public function __construct(AbstractProvider $pro, CsrfProviderInterface $csrfService)
    {
        $this->provider = $pro;
        $this->csrf = $csrfService;
    }

    public function getAuthorizationUrl()
    {
        $options = [self::STATE_KEY => $this->csrf->generateCsrfToken(__CLASS__)];

        return $this->provider->getAuthorizationUrl($options);
    }

    public function validateRequest(Request $req)
    {
        if ($this->csrf->isCsrfTokenValid(__CLASS__, $request->query->get(self::STATE_KEY, ''))) {
            throw new AuthenticationException("Invalid state");
        }
    }

}