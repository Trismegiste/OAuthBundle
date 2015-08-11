<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * OAuthServerController is test server for oauth
 */
class OAuthServerController extends Controller
{

    public function authorizeAction(Request $request)
    {
        if (!in_array('dummy', $this->get('oauth.provider.factory')->getAvaliableProvider())) {
            throw new AccessDeniedHttpException("U haxxor");
        }

        $url = $request->get('redirect');

        return $this->render('TrismegisteOAuthBundle:OAuthServer:oauth_authorize.html.twig', ['redirect' => $url]);
    }

}
