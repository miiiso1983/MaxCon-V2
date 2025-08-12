<?php

return [
    // Default tenant ID to use when no tenant could be identified by domain/subdomain
    // You can override this in your .env file with DEFAULT_TENANT_ID
    'default_tenant_id' => env('DEFAULT_TENANT_ID', null),

    // Central (primary) domain for subdomain-based tenancy
    // Used by TenantMiddleware::extractSubdomain
    'central_domain' => env('CENTRAL_DOMAIN', 'maxcon.app'),

    // Whether to enforce subscription/trial checks in middleware
    'enforce_subscription' => env('ENFORCE_SUBSCRIPTION', false),
];

