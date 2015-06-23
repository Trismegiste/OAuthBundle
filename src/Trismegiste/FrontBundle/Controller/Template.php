<?php

namespace Trismegiste\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Design pattern : Template Method
 *
 * This is a template for a controller for the default rendering of a 
 * twitter bootstrap layout (white w/ simple black top menu)
 */
abstract class Template extends Controller
{

    /**
     * Action for the homepage
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('TrismegisteFrontBundle:Default:index.html.twig');
    }

    /**
     * You need to implement this method in order to return an array for the top menu
     *
     * @return array an array of key/value : key is the label and value is the route
     */
    abstract protected function getTopMenu();

    /**
     * Adds some data to the page before its rendering
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['topmenu'] = $this->getTopMenu();

        return parent::render($view, $parameters, $response);
    }

}
