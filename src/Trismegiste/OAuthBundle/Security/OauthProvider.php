<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Security;

use Exception;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationServiceException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * OauthProvider is symfony security AuthenticationProvider
 */
class OauthProvider implements AuthenticationProviderInterface
{

    /** @var \Trismegiste\OAuthBundle\Security\OauthUserProviderInterface */
    protected $userProvider;

    /** @var string */
    protected $firewallName;

    public function __construct(OauthUserProviderInterface $repository, $firewallName)
    {
        $this->userProvider = $repository;
        $this->firewallName = $firewallName;
    }

    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return;
        }
        /* @var $token \Trismegiste\OAuthBundle\Security\Token */

        try {
            $found = $this->userProvider
                    ->findByOauthId($token->getProviderKey(), $token->getUserUniqueIdentifier());
        } catch (Exception $notFound) {
            throw new BadCredentialsException('Bad credentials', 0, $notFound);
        }

        if (!$found instanceof UserInterface) {
            throw new AuthenticationServiceException('findByOauthId() must return a UserInterface.');
        }

        $authenticatedToken = new Token($this->firewallName, $token->getProviderKey(), $token->getUserUniqueIdentifier(), $found->getRoles());
        $authenticatedToken->setAttributes($token->getAttributes());
        $authenticatedToken->setUser($found);

        return $authenticatedToken;
    }

    public function supports(TokenInterface $token)
    {
        var_dump($token->getFirewallName(), $this->firewallName);
        return ($token instanceof Token) && ($token->getFirewallName() === $this->firewallName);
    }

}
