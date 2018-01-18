<?php

/**
 * Singleton configuration class produces holds config variables.
 */
class SConfig
{
	private static $_config = null;
	private static $config = array();

	private function __construct()
	{
	}

	public static function getConfig()
	{
		if (self::$_config == null) :
			self::$_config = new SConfig();
		endif;

		return self::$_config;
	}

	public static function set($key, $val)
	{
		self::$config[$key] = $val;
	}

	public static function get($key)
	{
		return self::$config[$key];
	}
}