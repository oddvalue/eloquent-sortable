<?php

namespace Oddvalue\EloquentSortable;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Oddvalue\EloquentSortable\Commands\EloquentSortableCommand;

class EloquentSortableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name('eloquent-sortable');
    }
}
