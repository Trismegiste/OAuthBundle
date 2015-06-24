<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

/**
 * OauthSecurityFactory is a factory for all OAuth components required for Symfony security layer
 * 
 * => sous classe de AbstractFactory pour le remeber me ?
 */
class OauthSecurityFactory implements SecurityFactoryInterface
{

    public function addConfiguration(NodeDefinition $builder)
    {
        
    }

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'security.authentication.provider.oauth.' . $id;
        $container
                ->setDefinition($providerId, new DefinitionDecorator('oauth.security.authentication.provider'))
                ->replaceArgument(0, new Reference($userProvider));

        $listenerId = 'security.authentication.listener.oauth.' . $id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('oauth.security.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
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