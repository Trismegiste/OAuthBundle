<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\DependencyInjection;

/**
 * ProviderConfigInterface is
 */
interface ProviderConfigInterface
{

    /**
     * Gets all provider config
     *
     * @return array an key with provider's key as keys and key pairs [public,secret] as value
     */
    public function all();
}
