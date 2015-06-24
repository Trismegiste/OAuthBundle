<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * OauthSecurityFactory is a factory for all OAuth components required for Symfony security layer
 */
class OauthSecurityFactory extends AbstractFactory
{

    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $provider = 'security.authentication.provider.oauth.' . $id;
        $container
                ->setDefinition($provider, new DefinitionDecorator('oauth.security.authentication.provider'))
                ->replaceArgument(0, new Reference($userProviderId));

        return $provider;
    }

    protected function getListenerId()
    {
        return 'oauth.security.authentication.listener';
    }

    public function getKey()
    {
        return 'oauth';
    }

    public function getPosition()
    {
        return 'form';
    }

}