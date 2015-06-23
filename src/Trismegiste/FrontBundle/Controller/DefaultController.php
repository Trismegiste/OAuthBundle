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

    public function connectWith($provider)
    {
        $factory = $this->get('oauth.provider.factory');

        return new RedirectResponse();
    }

}
