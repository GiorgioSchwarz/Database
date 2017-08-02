<?php

namespace MolnApps\Database\Container;

class Container 
{
	private static $instance;

	private function __construct()
	{
		$this->container = new \Pimple\Container;
		$this->container->register(new DatabaseProvider);
	}

	private static function instance()
	{
		if ( ! static::$instance) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	public static function get($key)
	{
		return static::instance()->getKey($key);
	}

	public static function reset()
	{
		static::$instance = null;
	}

	private function getKey($key)
	{
		return $this->container[$key];
	}
}