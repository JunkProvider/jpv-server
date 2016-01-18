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
class ServiceProvider
{
	/**
	 * An associative array. The key is the product class name and the value
	 * the factory class name.
	 * 
	 * @var string[string]
	 */
	private $factoryMap = [];
	
	/**
	 * @var Object[string]
	 */
	private $serviceCache = [];
	
	/**
	 * @param string[string] $factoryMap An associative array. The key is the product class name and the value the FactoryInterface class name.
	 */
	public function __construct($factoryMap = [])
	{
		$this->factoryMap = $factoryMap;
	}
	
	/**
	 * @param Object $service
	 */
	public function registerService($service)
	{
		$this->serviceCache[get_class($service)] = $service;
	}
	
	/**
	 * @param string $serviceClassName
	 * @param string $factoryClassName
	 */
	public function registerFactory($serviceClassName, $factoryClassName)
	{
		$this->factoryMap[$serviceClassName] = $factoryClassName;
	}
	
	/**
	 * 
	 * @param string $className
	 * 
	 * @throws \Exception
	 * @return object
	 */
	public function provide($className)
	{
		//var_dump($this->factoryMap);
		//die();
		
		// Return the service if it was already created.
		if (isset($this->serviceCache[$className]))
		{
			return $this->serviceCache[$className];
		}
		
		// Escape from the current namespace.
		$fullClassName = '\\' . $className;
		
		// Check if the service exists.
		if (!class_exists($fullClassName, true))
		{
			throw new \Exception('service does not exist: ' . $className);
		}

		/* @var $factory FactoryInterface */
		$factory = $this->getFactory($className);
		
		$service = null;
		
		if ($factory !== null)
		{
			$service = $factory->produce($this, $className);
			if ($service === null)
			{
				throw new \Exception('Factory did not return a service: ' . get_class($factory));
			}
		}
		else
		{
			$service = new $fullClassName();
		}

		$this->serviceCache[$className] = $service;
		
		return $service;
	}

	/**
	 * @param string $productClassName
	 * 
	 * @return FactoryInterface
	 */
	private function getFactory($productClassName)
	{
		$className = $this->getFactoryClassName($productClassName);

		$fullClassName = '\\' . $className;
		
		if (!class_exists($className, true))
		{
			return null;	
		}
		
		return new $fullClassName();
	}
	
	/**
	 * @param string $productclassName
	 * 
	 * @return string
	 */
	private function getFactoryClassName($productClassName)
	{
		if (isset($this->factoryMap[$productClassName]))
		{
			return $this->factoryMap[$productClassName];
		}
		
		return $productClassName . 'Factory';
	}
}