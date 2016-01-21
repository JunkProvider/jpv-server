<?php

/**
 * @version    SVN: $Id$
 * @author     Nico Englert <junkProvider@outlook.com>
 * @copyright  Nico Englert <junkProvider@outlook.com>
 */

namespace JPV\View;

use JPV\Service\FactoryInterface;
use JPV\Service\ServiceProvider;

/**
 * @author Nico Englert <junkProvider@outlook.com>
 */
class RendererFactory implements FactoryInterface
{
	/**
	 * @param ServiceProvider $serviceProvider
	 * @param string          $productClassName
	 *
	 * @return Object
	 */
	public function produce(ServiceProvider $serviceProvider, $productClassName)
	{
		return new Renderer();
	}
}
