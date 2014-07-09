<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

interface IControllerFactory
{
    /**
     * @param \System\Web\HttpContext $context
     * @param string $controllerName
     * @return ControllerBase
     */
	public function CreateController(HttpContext $context, $controllerName);
}