<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Token is a security token created with redirect_uri from a resource owner
 */
class Token extends AbstractToken
{

    const UNIQUE_ID_ATTR = 'uniqueId';
    const PROVIDER_KEY_ATTR = 'providerKey';
    const FIREWALL_NAME_ATTR = 'firwallName';

    public function __construct($firewallName, $providerKey, $uid, array $role = [])
    {
        parent::__construct($role);
        $this->setAuthenticated(count($role) > 0);
        $this->setAttribute(self::UNIQUE_ID_ATTR, $uid);
        $this->setAttribute(self::PROVIDER_KEY_ATTR, $providerKey);
        $this->setAttribute(self::FIREWALL_NAME_ATTR, $firewallName);
    }

    public function getCredentials()
    {
        return '';
    }

    public function getUserUniqueIdentifier()
    {
        return $this->getAttribute(self::UNIQUE_ID_ATTR);
    }

    public function getProviderKey()
    {
        return $this->getAttribute(self::PROVIDER_KEY_ATTR);
    }

    public function getFirewallName()
    {
        return $this->getAttribute(self::FIREWALL_NAME_ATTR);
    }

}
