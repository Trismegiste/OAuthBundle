<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * OAuthServerController is test server for oauth
 */
class OAuthServerController extends Controller
{

    public function authorizeAction(Request $request)
    {
        $url = $request->get('redirect');

        return $this->render('TrismegisteFrontBundle:Default:oauth_authorize.html.twig', ['redirect' => $url]);
    }

}