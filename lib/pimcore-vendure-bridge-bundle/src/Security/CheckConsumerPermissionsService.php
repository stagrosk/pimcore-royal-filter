<?php

namespace PimcoreVendureBridgeBundle\Security;

use Symfony\Component\HttpFoundation\Request;

class CheckConsumerPermissionsService
{
    public const TOKEN_HEADER = 'X-API-Key';

    private string $securityApiKey;

    /**
     * @param string $securityApiKey
     */
    public function __construct(
        string $securityApiKey
    ) {
        $this->securityApiKey = $securityApiKey;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function performSecurityCheck(Request $request): bool
    {
        $apiKey = $request->headers->get('apikey');
        if (empty($apiKey)) {
            $apiKey = $request->headers->get(static::TOKEN_HEADER);
        }
        if (empty($apiKey)) {
            $apiKey = $request->get('apikey');
        }

        return $apiKey === $this->securityApiKey;
    }
}
