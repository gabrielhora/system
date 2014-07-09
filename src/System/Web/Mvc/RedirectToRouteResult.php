<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;

class RedirectToRouteResult extends ActionResult
{
    private $routeName;
    private $controllerName;
    private $actionName;
    private $routeValues;
    private $permanent;

    public function __construct($routeName, $controllerName = null, $actionName = null, array $routeValues = array(), $permanent = false)
    {
        $this->routeName = $routeName;
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
        $this->routeValues = $routeValues;
        $this->permanent = $permanent;
    }

    function ExecuteResult(HttpContext $context, ControllerBase $controller)
    {
        $urlHelper = new UrlHelper($context);
        $url = $urlHelper->Action($this->routeName, $this->controllerName, $this->actionName, $this->routeValues);
        if ($url === null) throw new \Exception("Route {$this->routeName} not found");

        $context->Response()->SetStatusCode($this->permanent ? 301 : 302);
        $context->Response()->Headers()->Add('Location', $url);
    }
}