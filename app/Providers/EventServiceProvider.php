<?php

namespace App\Providers;

use App\Events\NotebookApplicationStarted;
use App\Events\NotebookTunnellingComplete;
use App\Listeners\StartNgrokProcess;
use App\Listeners\UpdateNotebookOnStart;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        NotebookApplicationStarted::class => [
            StartNgrokProcess::class,
        ],
        NotebookTunnellingComplete::class => [
            UpdateNotebookOnStart::class,
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

        //
    }
}
