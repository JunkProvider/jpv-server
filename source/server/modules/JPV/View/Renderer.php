<?php

/**
 * @version    SVN: $Id$
 * @author     Nico Englert <junkProvider@outlook.com>
 * @copyright  Nico Englert <junkProvider@outlook.com>
 */

namespace JPV\View;

/**
 * @author Nico Englert <junkProvider@outlook.com>
 */
class Renderer
{
	/**
	 *
	 * @param string $view  The path to the view file.
	 * @param mixed  $model The model, can be whatever the view requires for rendering.
	 *
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	public function render($view, $model = null)
	{
		if (!file_exists($view))
			throw new \InvalidArgumentException('Could not render view. View file ' . $view . ' not found.');

		ob_start();

		include $view;

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}
