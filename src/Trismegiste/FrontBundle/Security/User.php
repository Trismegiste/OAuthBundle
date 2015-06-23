<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User is 
 */
class User implements UserInterface
{

    protected $info;

    public function __construct(UserResponseInterface $info)
    {
        $this->info = $info;
    }

    public function eraseCredentials()
    {
        
    }

    public function getPassword()
    {
        
    }

    public function getRoles()
    {
        return ['ROLE_ADMIN'];
    }

    public function getSalt()
    {
        
    }

    public function getUsername()
    {
        return $this->info->getUsername();
    }

}