<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('firebase', function ($app) {
            $serviceAccount = ServiceAccount::fromJsonFile(storage_path().'/rsu-arborizacion-firebase-adminsdk-vx005-3a6af78310.json');
            $firebase = (new Factory)
                ->withServiceAccount($serviceAccount)
                ->create();

            return $firebase;
        });
    }
}
