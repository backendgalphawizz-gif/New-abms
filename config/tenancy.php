<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;

$centralDomains = array_values(array_filter(array_map('trim', explode(',', env('TENANCY_CENTRAL_DOMAINS', '127.0.0.1,localhost')))));

return [
    'tenant_model' => \App\Models\Entity::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    'domain_model' => Domain::class,

    'central_domains' => $centralDomains,

    /**
     * Host suffix for new tenant domains: {subdomain}.{TENANCY_BASE_DOMAIN}
     * Example: TENANCY_BASE_DOMAIN=saas.test → client.saas.test
     */
    'entity_domain_suffix' => env('TENANCY_BASE_DOMAIN', 'localhost'),

    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
    ],

    'database' => [
        'central_connection' => env('TENANCY_CENTRAL_CONNECTION', 'central'),

        'template_tenant_connection' => env('TENANCY_TEMPLATE_CONNECTION', 'mysql'),

        'prefix' => env('TENANCY_DB_PREFIX', 'tenant'),
        'suffix' => '',

        'managers' => [
            'sqlite' => Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager::class,
            'mysql' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'pgsql' => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager::class,
        ],
    ],

    'cache' => [
        'tag_base' => 'tenant',
    ],

    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
        ],
        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],
        'suffix_storage_path' => true,
        // When true, Stancl rewires asset() to a tenancy asset route; if that route is not
        // registered or you share public/ across tenants, CSS/JS/images 404. Use false
        // so asset() resolves like a normal Laravel app on the current host.
        'asset_helper_tenancy' => false,
    ],

    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [],
    ],

    'features' => [],

    'migration_parameters' => [
        '--force' => true,
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],

    'seeder_parameters' => [
        '--class' => 'DatabaseSeeder',
    ],
];
