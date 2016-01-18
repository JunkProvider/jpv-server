<?php

/**
 * @version    SVN: $Id$
 * @author     Nico Englert <junkProvider@outlook.com>
 * @copyright  Nico Englert <junkProvider@outlook.com>
 */

namespace JPV\Service;

/**
 * @author Nico Englert <junkProvider@outlook.com>
 */
class ConfigProvider
{
	/**
	 * @var string
	 */
	private $configPath;

	/**
	 * @var mixed[string]
	 */
	private $config = null;

	/**
	 * @param string $configPath
	 */
	public function __construct($configPath)
	{
		$this->configPath = $configPath;
	}

	/**
	 * @param string[] $keyPath
	 *
	 * @throws \Exception
	 * @return unknown
	 */
	public function provide($keyPath = [])
	{
		if ($this->config === null)
		{
			$this->config = $this->loadConfig();
		}

		$config = &$this->config;

		if (count($keyPath) === 0)
			return $config;

		foreach ($keyPath as $key)
		{
			if (!isset ($config[$key]))
			{
				throw new \Exception('Could not get config. Config "' . implode('->', $keyPath) . '" not set.');
			}

			$config = &$config[$key];
		}

		return $config;
	}

	/**
	 * @return mixed[string]
	 */
	private function loadConfig()
	{
		$config = [];
		foreach (glob($this->configPath . "/*.php") as $filename)
		{
			$partialConfig = include ($filename);
			$config = array_merge($config, $partialConfig);
		}
		return $config;
	}
}
