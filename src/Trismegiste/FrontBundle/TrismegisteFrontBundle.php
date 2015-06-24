<?php

namespace Trismegiste\FrontBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Trismegiste\FrontBundle\Security\OauthSecurityFactory;

class TrismegisteFrontBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OauthSecurityFactory());
    }

}
