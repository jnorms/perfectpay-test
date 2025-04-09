<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Client;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Http::macro('asaas', function () {
            return Http::withHeaders([
                 'User-Agent' => 'perfect-pay-app-test',
                 'access_token' => config('services.asaas.token'),
             ])
                ->throw()
                ->asJson()
                ->baseUrl(config('services.asaas.uri'));
        });
        
        Relation::enforceMorphMap(
            [
                'address'  => Address::class,
                'client'  => Client::class,
            ]
        );
    }
}
