<?php

namespace Lokielse\LaravelSLS;

use Aliyun\SLS\Client;
use Illuminate\Support\ServiceProvider;

class LaravelSLSServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([ realpath(__DIR__ . '/../config/sls.php') => config_path('sls.php') ]);
    }


    /**
     * Add the connector to the queue drivers.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sls', function ($app) {
            $config = $app['config']['sls'];

            $accessKeyId     = array_get($config, 'access_key_id');
            $accessKeySecret = array_get($config, 'access_key_secret');
            $endpoint        = array_get($config, 'endpoint');
            $project         = array_get($config, 'project');
            $store           = array_get($config, 'store');

            $client = new Client($endpoint, $accessKeyId, $accessKeySecret);

            $log = new SLSLog($client);
            $log->setProject($project);
            $log->setLogStore($store);

            return $log;
        });

        $config = $this->app['config']['sls'];

        $this->app->instance('sls.writer', new Writer(app('sls'), $this->app['events'], $config['topic']));
    }
}
