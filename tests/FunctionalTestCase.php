<?php

declare(strict_types=1);

namespace Umbrellio\Postgres\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Umbrellio\Postgres\Tests\Functional\TestUtil;

abstract class FunctionalTestCase extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('database.default', 'main');

        $this->setConnectionConfig($app, 'main', TestUtil::getParamsForMainConnection());
        $this->setConnectionConfig($app, 'temporary', TestUtil::getParamsForTemporaryConnection());
    }

    private function setConnectionConfig($app, $name, $params)
    {
        $app['config']->set('database.connections.' . $name, [
            'driver' => 'pgsql',
            'host' => $params['host'],
            'port' => (int) $params['port'],
            'database' => $params['dbname'],
            'username' => $params['user'],
            'password' => $params['password'],
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ]);
    }
}
