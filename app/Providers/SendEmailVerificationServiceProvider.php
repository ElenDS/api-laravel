<?php

declare(strict_types=1);

namespace App\Providers;

use App\Jobs\SendEmailVerificationJob;
use Illuminate\Support\ServiceProvider;

class SendEmailVerificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app->bindMethod([SendEmailVerificationJob::class, 'handle'], function (SendEmailVerificationJob $job) {
            $job->handle();
        });
    }
}
