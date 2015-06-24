<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Token is a security token created with redirect_uri from a resource owner
 */
class Token extends AbstractToken
{

    /** @var AccessToken */
    private $accessToken;

    /** @var string */
    private $providerKey;

    /** @var array */
    private $userInfo;

    public function __construct($providerKey, AccessToken $accessToken, array $role = [])
    {
        parent::__construct($role);
        $this->setAuthenticated(count($role) > 0);
        $this->accessToken = $accessToken;
        $this->providerKey = $providerKey;
    }

    public function getCredentials()
    {
        return $this->accessToken;
    }

    public function setUserInfo($data)
    {
        $this->userInfo = $data;
    }

    public function getUserUniqueIdentifier()
    {
        return $this->userInfo->uid;
    }

    public function getProviderKey()
    {
        return $this->providerKey;
    }

}