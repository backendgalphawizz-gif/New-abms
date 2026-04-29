<?php

declare(strict_types=1);

namespace App\Tenancy\Console;

use Illuminate\Database\Migrations\Migrator;
use Stancl\Tenancy\Commands\Rollback as StanclTenantsRollback;

/**
 * Avoid Stancl's duplicate specifyParameters() on tenants:rollback.
 */
class TenantsRollbackCommand extends StanclTenantsRollback
{
    public function __construct(Migrator $migrator)
    {
        \Illuminate\Database\Console\Migrations\RollbackCommand::__construct($migrator);

        $this->setName('tenants:rollback');
    }
}
