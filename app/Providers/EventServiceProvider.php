<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        if (! class_exists(Events\TenantCreated::class)) {
            return;
        }

        Event::listen(Events\TenantCreated::class, JobPipeline::make([
            Jobs\CreateDatabase::class,
            Jobs\MigrateDatabase::class,
        ])->send(function (Events\TenantCreated $event) {
            return $event->tenant;
        })->shouldBeQueued(false)->toListener());

        Event::listen(Events\TenantDeleted::class, JobPipeline::make([
            Jobs\DeleteDatabase::class,
        ])->send(function (Events\TenantDeleted $event) {
            return $event->tenant;
        })->shouldBeQueued(false)->toListener());
    }
}
