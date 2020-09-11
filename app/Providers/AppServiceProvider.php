<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $unsortedFiles = Storage::disk('documents')->allFiles('unsorted');
        $sortedFiles = Storage::disk('documents')->allFiles('sorted');

        View::share('_unsorted_files', $unsortedFiles);
        View::share('_sorted_files', $sortedFiles);
    }
}
