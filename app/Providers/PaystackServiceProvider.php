<?php

namespace App\Providers;

use App\Http\Clients\PaystackClient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\ServiceProvider;

class PaystackServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton(PaystackClient::class, function (){
            $client = new PaystackClient();

            $client->baseUrl(config('services.paystack.base_url'))
                ->acceptJson()
                ->withToken(config('services.paystack.secret_key'));

            return $client;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
    }
}
