<?php
namespace System\Web\Routing;

use System\Web\Mvc\DefaultControllerFactory;
use System\Web\Mvc\MvcHandler;

class RouteCollection
{
    /** @var Route[] */
    private $routes = array();

    /** @var string */
    private $namespace;

    public function SetControllerNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function ControllerNamespace()
    {
        return $this->namespace;
    }

    public function Routes()
    {
        return $this->routes;
    }

    public function Get($name)
    {
        return array_key_exists($name, $this->routes) ? $this->routes[$name] : null;
    }

	public function Add($name, Route $route)
	{
		$this->routes[$name] = $route;
	}

	public function MapRoute($name, $url, array $defaults = array(), array $constraints = array())
	{
		$route = new Route($url, new MvcHandler(new DefaultControllerFactory()), $defaults, $constraints);
        $this->Add($name, $route);
	}
}