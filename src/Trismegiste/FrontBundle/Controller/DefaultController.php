<?php

namespace Trismegiste\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Example a simple controller with an About page
 *
 * Modify, override or subclass it
 */
class DefaultController extends Template
{

    public function aboutAction()
    {
        return $this->render('TrismegisteFrontBundle:Default:about.html.twig');
    }

    protected function getTopMenu()
    {
        return array('About' => 'trismegiste_about');
    }

    public function connectWithAction($provider)
    {
        $factory = $this->get('oauth.provider.factory');
        $provider = $factory->create('github');
        // If we don't have an authorization code then get one
        $authUrl = $provider->getAuthorizationUrl();
        $this->get('session')->set('oauth2state', $provider->state);

        return new RedirectResponse($authUrl);
    }

}
