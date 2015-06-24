<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * OauthProvider is symfony security AuthenticationProvider
 */
class OauthProvider implements AuthenticationProviderInterface
{

    /** @var \Trismegiste\FrontBundle\Security\OauthUserProviderInterface */
    protected $userProvider;

    public function __construct(OauthUserProviderInterface $repository)
    {
        $this->userProvider = $repository;
    }

    public function authenticate(TokenInterface $token)
    {
        if (!$this->supports($token)) {
            return;
        }
        /* @var $token \Trismegiste\FrontBundle\Security\Token */

        try {
            $found = $this->userProvider->findByOauthId($token->getProviderKey(), $token->getUserUniqueIdentifier());
        } catch (\Exception $e) {
            
        }
    }

    public function supports(TokenInterface $token)
    {
        return $token instanceof \Trismegiste\FrontBundle\Oauth\Token;
    }

}