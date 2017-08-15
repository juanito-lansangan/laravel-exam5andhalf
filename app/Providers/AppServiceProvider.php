<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // schema modify
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('App\Repositories\Pet\IPetRepository', 'App\Repositories\Pet\PetRepository');
        $this->app->bind('App\Repositories\Category\ICategoryRepository', 'App\Repositories\Category\CategoryRepository');
        $this->app->bind('App\Repositories\Tag\ITagRepository', 'App\Repositories\Tag\TagRepository');
    }
}
