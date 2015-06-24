<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * OauthUser is ...
 *
 * @author flo
 */
class OauthUser implements UserInterface
{

    protected $name;

    public function __construct($str)
    {
        $this->name = $str;
    }

    public function eraseCredentials()
    {
        
    }

    public function getPassword()
    {
        
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        
    }

    public function getUsername()
    {
        return $this->name;
    }

}