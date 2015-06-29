<?php

/*
 * OAuthBundle
 */

namespace Trismegiste\OAuthBundle\Security;

/**
 * OauthUserProvider is a contract for a provider of User that can be retrieve with OAuth information
 */
interface OauthUserProviderInterface
{

    public function findByOauthId($provider, $uid);
}