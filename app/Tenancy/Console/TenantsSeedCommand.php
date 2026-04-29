<?php

declare(strict_types=1);

namespace App\Tenancy\Console;

use Illuminate\Database\ConnectionResolverInterface;
use Stancl\Tenancy\Commands\Seed as StanclTenantsSeed;

/**
 * Avoid Stancl's duplicate specifyParameters() on tenants:seed.
 */
class TenantsSeedCommand extends StanclTenantsSeed
{
    public function __construct(ConnectionResolverInterface $resolver)
    {
        \Illuminate\Database\Console\Seeds\SeedCommand::__construct($resolver);

        $this->setName('tenants:seed');
    }
}
