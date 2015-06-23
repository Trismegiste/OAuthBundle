<?php

use Trismegiste\Prelude\Kernel;

class AppKernel extends Kernel
{

    public function registerAdditionalBundles()
    {
        return [
            new Trismegiste\FrontBundle\TrismegisteFrontBundle(),
            new Trismegiste\DokudokiBundle\TrismegisteDokudokiBundle(),
        ];
    }

}
