<?php
namespace System\Web\Mvc;

use System\Web\HttpContext;
use System\Web\Routing\RouteCollection;
use System\Web\Routing\RouteTable;

class UrlHelper
{
    /** @var HttpContext */
    private $httpContext;

    /** @var RouteCollection */
    private $routeCollection;

    /**
     * @param HttpContext $context
     */
    public function __construct(HttpContext $context)
    {
        $this->httpContext = $context;
        $this->routeCollection = RouteTable::Routes();
    }

    /**
     * @param $routeName
     * @param null $controllerName
     * @param null $actionName
     * @param array $routeValues
     * @return null|string
     */
    public function Action($routeName, $controllerName = null, $actionName = null, array $routeValues = array())
    {
		$route = $this->routeCollection->Get($routeName);
        if ($route === null) return null;

        $componentValues = array('controller' => $controllerName, 'action' => $actionName) + $routeValues;
        $url = $route->BuildUrl($componentValues);

        if ($url === false) return null;
        return strtolower($url);
    }
} 