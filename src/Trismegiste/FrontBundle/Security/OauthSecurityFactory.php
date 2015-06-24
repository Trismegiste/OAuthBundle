<?php

/*
 * connect-oauth
 */

namespace Trismegiste\FrontBundle\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * OauthSecurityFactory is a factory for all OAuth components required for Symfony security layer
 */
class OauthSecurityFactory implements SecurityFactoryInterface
{

    public function addConfiguration(NodeDefinition $builder)
    {
        
    }

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        
    }

    public function getKey()
    {
        
    }

    public function getPosition()
    {
        
    }

}