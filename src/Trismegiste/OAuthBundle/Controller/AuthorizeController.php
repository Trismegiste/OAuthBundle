<?php

/*
 * OAuthbundle
 */

namespace Trismegiste\OAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * AuthorizeController is a gateway to authentication with the provider
 */
class AuthorizeController extends Controller
{

    public function connectWithAction($provider)
    {
        $factory = $this->get('oauth.provider.factory');
        $provider = $factory->create($provider);
        $authUrl = $provider->getAuthorizationUrl();

        return new RedirectResponse($authUrl);
    }

}
