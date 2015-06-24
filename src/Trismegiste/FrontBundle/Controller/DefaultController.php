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
        print_r($this->get('security.context')->getToken());
        return array('About' => 'trismegiste_about');
    }

    public function connectWithAction($provider)
    {
        $factory = $this->get('oauth.provider.factory');
        $provider = $factory->create($provider);
        // If we don't have an authorization code then get one
        $authUrl = $provider->getAuthorizationUrl();

        return new RedirectResponse($authUrl);
    }

    public function adminAction()
    {
        return $this->render('TrismegisteFrontBundle:Default:admin.html.twig');
    }

}
