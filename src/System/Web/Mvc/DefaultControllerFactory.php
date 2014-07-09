<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class DefaultControllerFactory implements IControllerFactory
{
	public function CreateController(HttpContext $context, $controllerName)
	{
        $routeData = $context->RouteData();
        $className = rtrim($routeData->ControllerNamespace(), '\\') . '\\' . ucfirst($routeData->ControllerName()) . 'Controller';
        return new $className;
	}
}