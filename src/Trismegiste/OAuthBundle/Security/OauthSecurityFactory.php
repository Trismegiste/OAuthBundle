<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Security;

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
                ->replaceArgument(0, new Reference($userProviderId))
                ->replaceArgument(1, $id);

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

    protected function createEntryPoint($container, $id, $config, $defaultEntryPoint)
    {
        $entryPointId = 'security.authentication.oauth_entry_point.' . $id;
        $container
                ->setDefinition($entryPointId, new DefinitionDecorator('oauth.security.authentication.entry_point'))
                ->addArgument(new Reference('security.http_utils'))
                ->addArgument($config['login_path'])
        ;

        return $entryPointId;
    }

}