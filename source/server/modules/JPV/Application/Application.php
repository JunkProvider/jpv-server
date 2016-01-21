<?php

/**
 * @version    SVN: $Id$
 * @author     Nico Englert <junkProvider@outlook.com>
 * @copyright  Nico Englert <junkProvider@outlook.com>
 */

namespace JPV\Application;

use Exception;
use JPV\Service\ServiceProvider;
use JPV\Service\ConfigProvider;

/**
 *
 * Initilizes the application and handles the request.
 *
 * @author Nico Englert <junkProvider@outlook.com>
 */
class Application
{
	/**
	 * @var ServiceProvider
	 */
	private $serviceProvider;

	/**
	 * @var ConfigProvider
	 */
	private $configProvider;

	/**
	 * @var string[string]
	 */
	private $request;

	public function __construct()
	{
		$this->configProvider = new ConfigProvider("source/server/config");
		$factories = $this->configProvider->provide([ 'factories' ]);

		$this->serviceProvider = new ServiceProvider($factories);
		$this->serviceProvider->registerService($this->configProvider);

		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			$this->request = $_POST;
		}
		else
		{
			$this->request = $_GET;
		}
	}

	/**
	 * Handles the request and returns the response as json string.
	 *
	 * @return string
	 */
	public function run()
	{
		// Start the output buffer for "catching" fatal errors.
		ob_start();

		$data = null;
		$exception = null;

		try
		{
			$data = $this->invokeAction();
		}
		catch (Exception $e)
		{
			$exception = $e;
		}

		$bufferContent = ob_get_clean();

		if ($this->isAjaxRequest())
		{
			$json = [
				'data' => $data,
				'buffer' => $bufferContent,
			];

			if ($exception !== null)
			{
				$json['exception'] = [
					'message' => $exception->getMessage(),
					'file' => $exception->getFile(),
					'line' => $exception->getLine(),
				];
			}

			return json_encode($json);
		}
		else
		{
			$html = '';

			if ($exception !== null)
			{
				$html .= 'Exception: ' . $exception->getMessage() . '<br/>';
				$html .= 'File: ' . $exception->getFile() . '<br/>';
				$html .= 'Line: ' . $exception->getLine() . '<br/>';
			}
			else if ($bufferContent !== '')
			{
				$html .= 'Buffer Content: ' . $bufferContent . '<br/>';
			}
			else
			{
				$html = $data;
			}

			return $html;
		}
	}

	/**
	 * Handles the request and returns the response.
	 *
	 * @return unknown
	 */
	private function invokeAction()
	{
		$defaultControllerName = $this->configProvider->provide([ 'application', 'defaultController' ]);
		$defaultActionName = $this->configProvider->provide([ 'application', 'defaultAction' ]);

		$controllerName = $this->getParameter('c', $defaultControllerName);
		$actionName = $this->getParameter('a', $defaultActionName);
		$actionParametersString = $this->getParameter('p', '[]');

		$fullControllerName = '\\' . $controllerName;
		$controller = new $fullControllerName($this->serviceProvider);

		if (!method_exists($controller, $actionName)) {
			throw new Exception('Could not invoke action. Controller "' . $controllerName . '" has no function named "' . $actionName . '"');
		}

		$actionParameters = json_decode($actionParametersString);
		if ($actionParameters === null)
		{
			$actionParameters = [];
		}

		$data = call_user_func_array([$controller, $actionName], $actionParameters);

		return $data;
	}

	/**
	 * Gets the parameter with the given key.
	 * Returns the given default value if the parameter is not set.
	 *
	 * @param string $key
	 * @param string $default Optional. Defaults null.
	 *
	 * @return string|null
	 */
	private function getParameter($key, $default = null)
	{
		if (isset ($this->request[$key]))
		{
			return $this->request[$key];
		}

		return $default;
	}

	/**
	 * Gets the parameter with the given key.
	 * Throws an exception with the given message if the parameter is not set.
	 *
	 * @param string $key
	 * @param string $exceptionMessage
	 *
	 * @throws Exception
	 * @return string
	 */
	private function getParameterForce($key, $exceptionMessage)
	{
		if (isset ($this->request[$key]))
		{
			return $this->request[$key];
		}

		throw new Exception($exceptionMessage);
	}

	/**
	 * Checks if the current request is an ajax request.
	 *
	 * @return boolean
	 */
	private function isAjaxRequest()
	{
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
}
