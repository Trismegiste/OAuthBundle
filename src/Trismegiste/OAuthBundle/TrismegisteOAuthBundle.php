<?php

namespace Trismegiste\OAuthBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Trismegiste\OAuthBundle\Security\OauthSecurityFactory;

class TrismegisteOAuthBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OauthSecurityFactory());
    }

    /**
     * KISS
     */
    public function getContainerExtension()
    {
        return new DependencyInjection\TrismegisteOAuthExtension();
    }

}
