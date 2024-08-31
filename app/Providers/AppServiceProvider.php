<?php

namespace App\Providers;

use App\Repositories\interfaces\LogisticRepositoryInterface;
use App\Repositories\LogisticRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LogisticRepositoryInterface::class, LogisticRepository::class);
    }
}
