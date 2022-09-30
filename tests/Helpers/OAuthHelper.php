<?php

namespace Tests\Helpers;

use Laravel\Passport\ClientRepository;
use function url;

class OAuthHelper
{
    public static function getOAuthClient(string $provider): \Laravel\Passport\Client
    {
        $clientRepo = new ClientRepository();

        return $clientRepo->createPasswordGrantClient(
            null,
            'Laravel Password Grant ' . ucfirst($provider),
            url()->current(),
            $provider
        );
    }

    public static function getAdminOAuthClient()
    {
        return self::getOAuthClient('users');
    }

    public static function getClientsOAuthClient()
    {
        return self::getOAuthClient('clients');
    }
}
