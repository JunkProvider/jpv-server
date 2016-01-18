<?php

/**
 * @version    SVN: $Id$
 * @author     Nico Englert <junkProvider@outlook.com>
 * @copyright  Nico Englert <junkProvider@outlook.com>
 */

namespace JPV\Controller;

use JPV\Service\ServiceProvider;

/**
 * @author Nico Englert <junkProvider@outlook.com>
 */
class ActionController
{
	/**
	 * @var ServiceProvider
	 */
	private $serviceProvider;
	
	/**
	 * @param ServiceProvider $serviceProvider
	 */
	public function __construct(ServiceProvider $serviceProvider)
	{
		$this->serviceProvider = $serviceProvider;
	}
	
	/**
	 * @return ServiceProvider
	 */
	protected function getServiceProvider()
	{
		return $this->serviceProvider;
	}
}
