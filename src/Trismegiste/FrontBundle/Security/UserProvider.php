<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;

/**
 * UserProvider is 
 */
class UserProvider implements OAuthAwareUserProviderInterface
{

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        return new User($response);
    }

}