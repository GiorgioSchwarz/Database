<?php

namespace MolnApps\Database\Container;

use \Pimple\Container;
use \Pimple\ServiceProviderInterface;

use \MolnApps\Database\Config;

class DatabaseProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['tableGatewayFactory'] = function($c) {
        	return new \MolnApps\Database\TableGatewayFactory;
        };

        $container['dsn'] = function($c) {
        	return new \MolnApps\Database\Dsn(Config::driver(), Config::dsn());
        };

        $container['tableAdapterFactory'] = function($c) {
        	return new \MolnApps\Database\TableAdapterFactory;
        };
    }
}