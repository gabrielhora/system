<?php
namespace System\Web;

use System\Web\Routing\RouteData;

class HttpContext
{
    protected static $Instance;

    /** @var string */
    private $basePath;

    /** @var \System\Web\HttpRequest */
    private $httpRequest;

    /** @var \System\Web\HttpResponse */
    private $httpResponse;

    /** @var array */
    private $routeData;

    /**
     * @return HttpContext
     */
    public static function Current()
    {
        if (static::$Instance === null) {
            static::$Instance = new static;
        }
        return static::$Instance;
    }

    private function __construct()
    {
        $this->httpRequest = new HttpRequest();
        $this->httpResponse = new HttpResponse();
    }

    private function __clone()
    {
    }

    public function SetBasePath($path)
    {
        $this->basePath = $path;
    }

    public function BasePath()
    {
        return $this->basePath;
    }

    /**
     * @return HttpRequest
     */
    public function Request()
    {
        return $this->httpRequest;
    }

    /**
     * @return HttpResponse
     */
    public function Response()
    {
        return $this->httpResponse;
    }

    /**
     * @return RouteData
     */
    public function RouteData()
    {
        return $this->routeData;
    }

    /**
     * @param RouteData $routeData
     */
    public function SetRouteData(RouteData $routeData)
    {
        $this->routeData = $routeData;
    }
}