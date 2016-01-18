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
interface FactoryInterface
{
	/**
	 * @param ServiceProvider $serviceProvider
	 * @param string          $productClassName
	 * 
	 * @return Object
	 */
	public function produce(ServiceProvider $serviceProvider, $productClassName);
}
