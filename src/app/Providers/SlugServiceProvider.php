<?php

namespace SEO\Providers;

use Illuminate\Support\ServiceProvider;

class SlugServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return  void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'../../database/migrations');
    }
}
