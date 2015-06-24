<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * OauthUserProvider provides users
 */
class OauthUserProvider implements OauthUserProviderInterface, UserProviderInterface
{

    public function __construct()
    {
        
    }

    public function findByOauthId($provider, $uid)
    {
        return new OauthUser("$provider-$uid");
    }

    public function loadUserByUsername($username)
    {
        return new OauthUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'Trismegiste\FrontBundle\Security\OauthUser';
    }

}