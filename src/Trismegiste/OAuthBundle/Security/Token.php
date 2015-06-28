<?php

/*
 * connect-oauth
 */

namespace Trismegiste\OAuthBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Token is a security token created with redirect_uri from a resource owner
 */
class Token extends AbstractToken
{

    /** @var string */
    private $uniqueId;

    /** @var string */
    private $providerKey;

    public function __construct($providerKey, $uid, array $role = [])
    {
        parent::__construct($role);
        $this->setAuthenticated(count($role) > 0);
        $this->uniqueId = $uid;
        $this->providerKey = $providerKey;
    }

    public function getCredentials()
    {
        return '';
    }

    public function getUserUniqueIdentifier()
    {
        return $this->uniqueId;
    }

    public function getProviderKey()
    {
        return $this->providerKey;
    }

}