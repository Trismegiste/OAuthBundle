<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Token is a security token created with redirect_uri from a resource owner
 */
class Token extends AbstractToken
{

    /** @var string */
    private $accessToken;

    /** @var string */
    private $providerKey;
    private $userInfo;

    public function __construct($providerKey, $accessToken, array $role = [])
    {
        parent::__construct($role);
        $this->setAuthenticated(count($role) > 0);
        $this->accessToken = $accessToken;
        $this->providerKey = $providerKey;
    }

    public function getCredentials()
    {
        return '';
    }

    public function setUserInfo($data)
    {
        $this->userInfo = $data;
    }

}