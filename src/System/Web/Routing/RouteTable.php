<?php
namespace System\Web\Routing;

class RouteTable
{
    /** @var RouteCollection */
	private static $routes;

    /**
     * @return RouteCollection
     */
    public static function Routes()
	{
		if (static::$routes === null)
			static::$routes = new RouteCollection();
		return static::$routes;
	}

    /**
     * @param $uri string
     * @return bool|RouteData
     */
    public static function Match($uri)
    {
        foreach (static::$routes->Routes() as $routeName => $route)
        {
            $match = $route->Match($uri);
            if ($match === false) continue;

            $routeData = new RouteData();
            $routeData->SetRoute($route);
            $routeData->SetValues($match);
            $routeData->SetControllerNamespace(static::Routes()->ControllerNamespace());
            return $routeData;
        }
        return false;
    }
}